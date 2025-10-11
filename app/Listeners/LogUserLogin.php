<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogUserLogin
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Registra no log as informações do usuário que efetuou login
        Log::info('Usuário logado', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'nome' => $event->user->name,
            'data_hora' => now(),
        ]);
    }
}
