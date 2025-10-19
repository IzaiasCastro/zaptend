<?php

namespace App\Providers;

use App\Domain\Orders\Listeners\AuthExternalApi;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
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

        // 👇 Aqui tu registra as cores personalizadas
         FilamentColor::register(function () {
            return [
                'primary' => Color::hex('#0056B8'),   // azul principal
                'info'    => Color::hex('#0077E6'),   // azul claro
                'success' => Color::hex('#00C6A2'),   // verde turquesa
                'danger'  => Color::Rose,
                'warning' => Color::Amber,
                'gray'    => Color::Zinc,
            ];
        });

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
