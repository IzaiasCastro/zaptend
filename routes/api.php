<?php

use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/agendas', function (Request $request) {
    $agendas = [
        [
            'barbeiro' => 'Carlos',
            'horarios' => [
                ['hora' => '09:00', 'disponivel' => true],
                ['hora' => '10:00', 'disponivel' => false],
                ['hora' => '11:00', 'disponivel' => true],
            ],
        ],
        [
            'barbeiro' => 'Marcos',
            'horarios' => [
                ['hora' => '09:00', 'disponivel' => true],
                ['hora' => '10:00', 'disponivel' => true],
                ['hora' => '11:00', 'disponivel' => false],
            ],
        ],
    ];

    return response()->json([
        'success' => true,
        'data' => $agendas,
    ]);
});
