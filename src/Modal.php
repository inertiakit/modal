<?php

namespace Inertiakit\Modal;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Response;
use Inertia\Support\Header;

class Modal implements Responsable
{
    protected Router $router;

    protected string $baseUrl;

    public function __construct(
        protected string $component,
        protected array|Arrayable $props
    ) {

        $this->router = app(Router::class);

    }

    public function baseRoute(string $name, mixed $parameters = [], bool $absolute = true): static
    {
        $this->baseUrl = route($name, $parameters, $absolute);

        return $this;
    }

    public function render(): mixed
    {
        inertia()->share('modal', $this->props);

        return response()->json();

    }

    public function handleRoute(Request $request)
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
        $fakeRequest->setJson($request->json())
            ->setUserResolver(fn () => $request->getUserResolver())
            ->setRouteResolver(fn () => $baseRoute)
            ->setLaravelSession($request->session());

        app()->instance('request', $fakeRequest);

        $middleware = new SubstituteBindings(app('router'));

        return $middleware->handle($fakeRequest, fn () => $baseRoute->run());

    }

    /**
     * {@inheritDoc}
     */
    public function toResponse($request): \Illuminate\Http\Response|JsonResponse|\Inertia\Response
    {

        $component = $this->component($request);

        inertia()->share([
            'modal' => $component,
        ]);

        if (request()->header('X-Inertia') && request()->header('X-Inertia-Partial-Component')) {
            /** @phpstan-ignore-next-line */
            return inertia()->render(request()->header('X-Inertia-Partial-Component'));
        }

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

    protected function component($request): array
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

    protected function redirectUrl(): string
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
