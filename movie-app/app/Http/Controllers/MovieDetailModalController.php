<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertiakit\Modal\Modal;

final class MovieDetailModalController
{
    public function __invoke(): Modal
    {

        return Inertia::modal('movies/details-modal', [
            'message' => 'Hello World!',
            'title' => 'Movie Details',
        ])->baseRoute('home');
    }
}
