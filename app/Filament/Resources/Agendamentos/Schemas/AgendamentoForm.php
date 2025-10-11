<?php

namespace App\Filament\Resources\Agendamentos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AgendamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('data')
                    ->required(),
                TimePicker::make('horario')
                    ->required(),
                TextInput::make('nome')
                    ->required(),
                TextInput::make('telefone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('servico')
                    ->required(),
                TextInput::make('observacao'),
                TextInput::make('profissional_id')
                    ->required()
                    ->numeric(),
                TextInput::make('servico_id')
                    ->required()
                    ->numeric(),
                TextInput::make('agenda_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['pendente' => 'Pendente', 'confirmado' => 'Confirmado', 'cancelado' => 'Cancelado'])
                    ->default('pendente')
                    ->required(),
                Select::make('pagamento')
                    ->options(['pendente' => 'Pendente', 'pago' => 'Pago'])
                    ->default('pendente')
                    ->required(),
                TextInput::make('valor')
                    ->required()
                    ->numeric(),
                TextInput::make('metodo_pagamento'),
                TextInput::make('cliente_id')
                    ->numeric(),
            ]);
    }
}
