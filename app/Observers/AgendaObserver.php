<?php

namespace App\Observers;

use App\Models\Agenda;
use App\Services\ExternalApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgendaObserver
{
    protected $api;
    public function __construct(ExternalApiService $api)
    {
        $this->api = $api;
    }
    
    /**
     * Handle the Agenda "created" event.
     */
    public function created(Agenda $agenda): void
    {
        $this->notifyApi('created', $agenda);
    }

    /**
     * Handle the Agenda "updated" event.
     */
    public function updated(Agenda $agenda): void
    {
        Log::info('Agenda atualizada: ' . $agenda->id);
        $this->api->updateAssistent($agenda);
    }

    /**
     * Handle the Agenda "deleted" event.
     */
    public function deleted(Agenda $agenda): void
    {
        $this->notifyApi('deleted', $agenda);
    }

    /**
     * Handle the Agenda "restored" event.
     */
    public function restored(Agenda $agenda): void
    {
        //
    }

    /**
     * Handle the Agenda "force deleted" event.
     */
    public function forceDeleted(Agenda $agenda): void
    {
        //
    }

    private function notifyApi($action, $produto)
    {
        try {
            
        } catch (\Exception $e) {
            \Log::error("Erro ao chamar API externa: " . $e->getMessage());
        }
    }
}
