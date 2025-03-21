<?php

namespace App\Providers;

use App\Contracts\TriviaServiceInterface;
use App\Services\NumbersApiTriviaService;
use App\Services\EncryptionService;
use Illuminate\Support\ServiceProvider;

class TriviaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TriviaServiceInterface::class, NumbersApiTriviaService::class);
        $this->app->singleton(EncryptionService::class);
    }
} 