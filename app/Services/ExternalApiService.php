<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    private $apiKey;
    private $assistant_id;

    public function __construct()
    {
        $this->apiKey = config('services.api_key_gpt'); // chave da OpenAI
        $this->assistant_id = config('services.assistant_id_gpt'); // chave da OpenAI
    }

    /**
     * Retorna o header de autenticação para a API
     */
    public function getHeaders()
    {
        Log::info('Usando API Key: ' . substr($this->apiKey, 0, 10) . '...'); // Log parcial da chave para verificação
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Faz uma requisição GET à OpenAI (exemplo: listar modelos)
     */
    public function listModels()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get('https://api.openai.com/v1/models');

        if ($response->successful()) {
            Log::info('Resposta da API GPT: ' . $response->body());
            return $response->json();
        }

        throw new \Exception('Erro ao listar modelos: ' . $response->body());
    }
    public function updateAssistent($data)
    {
        $messages = [
            ['role' => 'user', 'content' => 'voce nao pode marcar horario no sabado, pois nao estara disponivel']
        ];
        $this->sendThreadMessages($messages);
    }

    public function sendThreadMessages(array $messages)
    {

         $result =[
        'instructions' => 'a pousada é fechada aos sabados'
    ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v2'
        ])->post("https://api.openai.com/v1/assistants/{$this->assistant_id}", $result);

        if ($response->successful()) {
            Log::info('Resposta da API GPT: ' . $response->body());
            return $response->json();
        }

        throw new \Exception('Erro ao modificar assistente: ' . $response->body());

    }

    /**
     * Retorna o token (no caso, a API key) - usando cache se quiser
     */
    public function getToken()
    {
        return Cache::remember('gpt_api_key', 60 * 60, fn() => $this->apiKey);
    }
}
