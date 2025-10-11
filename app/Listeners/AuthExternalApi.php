<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\ExternalApiService;
use Illuminate\Support\Facades\Log;

class AuthExternalApi
{
    protected $api;

    public function __construct(ExternalApiService $api)
    {
        $this->api = $api;
    }

    public function handle(Login $event): void
    {
        try {
            // Apenas pega o token (API Key) do service
            $token = $this->api->listModels();

            Log::info('Usuário logado: ' . $event->user->email);
            
            // Se quiser, você pode testar a API, por exemplo, listar modelos
            //$models = $this->api->listModels();
            //Log::info('Modelos disponíveis: ' . count($models['data']));
            
        } catch (\Exception $e) {
            Log::error('Erro ao acessar a API externa: ' . $e->getMessage());
        }
    }
}
