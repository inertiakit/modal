<?php

declare(strict_types=1);

namespace Inertiakit\Modal;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\ServiceProvider;
use Inertia\ResponseFactory;

class ModalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        ResponseFactory::macro('modal', function (string $component, array|Arrayable $props = []) {
            return new Modal(
                component: $component,
                props: $props
            );
        });
    }
}
