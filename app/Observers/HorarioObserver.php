<?php

namespace App\Observers;

use App\Models\Horario;
use App\Services\ExternalApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HorarioObserver
{
    protected $api;

    public function __construct(ExternalApiService $api)
    {
        $this->api = $api;
    }

    public function updated(Horario $horario)
    {
        Log::info('Horário atualizado: ' . $horario->id);

       

        // Enviar prompt para a OpenAI com cache ephemeral
        $this->api->updateAssistent($horario);
    }
}
