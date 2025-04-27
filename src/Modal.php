<?php

namespace Inertiakit\Modal;

use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Response;
use Inertia\Response as InertiaResponse;
use Inertia\Support\Header;
use Symfony\Component\HttpFoundation\InputBag;

use function app;

class Modal implements Responsable
{
    protected Router $router;

    protected string $baseUrl;

    /**
     * @param  array<string, mixed>|Arrayable<string, mixed>  $props
     */
    public function __construct(
        protected string $component,
        protected array|Arrayable $props
    ) {

        /** @var Router $router */
        $router = app('router');
        $this->router = $router;

    }

    public function baseRoute(string $name, mixed $parameters = [], bool $absolute = true): static
    {
        $this->baseUrl = route($name, $parameters, $absolute);

        return $this;
    }

    public function handleRoute(Request $request): mixed
    {

        $fakeRequest = Request::create(
            $this->baseUrl,
            'GET',
            $request->query->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->getContent(),
        );

        $baseRoute = $this->router->getRoutes()->match($fakeRequest);

        $fakeRequest->headers->replace($request->headers->all());

        $json = $request->json();

        assert($json instanceof InputBag);

        $fakeRequest->setJson($json)
            ->setUserResolver(fn () => $request->getUserResolver())
            ->setRouteResolver(fn () => $baseRoute)
            ->setLaravelSession($request->session());

        app()->instance('request', $fakeRequest);

        /** @var Registrar $router */
        $router = app('router');

        $middleware = new SubstituteBindings($router);

        return $middleware->handle($fakeRequest, fn () => $baseRoute->run());

    }

    /**
     * @param  Request  $request
     */
    public function toResponse($request): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|JsonResponse|InertiaResponse
    {

        $component = $this->component();

        /** @phpstan-ignore-next-line  */
        inertia()->share([
            'modal' => $component,
        ]);

        if (request()->header('X-Inertia') && request()->header('X-Inertia-Partial-Component')) {
            /** @phpstan-ignore-next-line */
            return inertia()->render(request()->header('X-Inertia-Partial-Component'));
        }

        /** @var Request $originalRequest */
        $originalRequest = app('request');

        $response = $this->handleRoute($originalRequest);

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        if ($request->header(Header::INERTIA)) {
            return new JsonResponse($component, 200, [Header::INERTIA => true]);
        }

        return Response::view('app');
    }

    /**
     * @return array<string, mixed>
     */
    protected function component(): array
    {
        return [
            'component' => $this->component,
            'props' => $this->props,
            'baseUrl' => $this->baseUrl,
            'redirectUrl' => $this->redirectUrl(),
            'version' => '1.0.0',
            'clearHistory' => false,
            'encryptHistory' => false,
        ];
    }

    /**
     * @return array<string,string>|string
     */
    protected function redirectUrl(): array|string
    {
        if (request()->header('X-Inertia-Modal-Redirect')) {
            return request()->header('X-Inertia-Modal-Redirect');
        }

        $referer = request()->headers->get('referer');

        if (request()->header('X-Inertia') && $referer && $referer != url()->current()) {
            return $referer;
        }

        return $this->baseUrl;

    }
}
