<?php

namespace App\Providers;

use App\Domain\Orders\Listeners\AuthExternalApi;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
        Log::info('Usuário logado teste: ', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'nome' => $event->user->name,
            'data_hora' => now(),
        ]);
        (new \App\Listeners\AuthExternalApi(app('App\Services\ExternalApiService')))->handle($event);
    });
    }
}
