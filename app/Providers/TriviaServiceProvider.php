<?php

namespace App\Providers;

use App\Contracts\TriviaServiceInterface;
use App\Services\NumbersApiTriviaService;
use Illuminate\Support\ServiceProvider;

class TriviaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TriviaServiceInterface::class, NumbersApiTriviaService::class);
    }
} 