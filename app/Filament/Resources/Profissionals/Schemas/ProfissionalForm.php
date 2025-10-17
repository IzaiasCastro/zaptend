<?php

namespace App\Filament\Resources\Profissionals\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProfissionalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Seção: Informações Pessoais
            Section::make('Informações Pessoais')
                ->schema([
                    TextInput::make('nome')
                        ->label('Nome completo')
                        ->placeholder('Digite o nome do profissional')
                        ->required(),
                ]),

            // Seção: Contato
            Section::make('Contato')
                ->schema([
                    TextInput::make('telefone')
                        ->label('Telefone')
                        ->placeholder('(99) 99999-9999')
                        ->tel()
                        // ->mask(fn ($mask) => $mask->pattern('(00) 00000-0000'))
                        ->required(),

                    TextInput::make('email')
                        ->label('E-mail')
                        ->placeholder('exemplo@dominio.com')
                        ->email()
                        ->required(),
                ]),

            // Seção: Endereço
            Section::make('Endereço')
                ->schema([
                    TextInput::make('cep')
                        ->label('CEP')
                        ->placeholder('00000-000')
                        // ->mask(fn ($mask) => $mask->pattern('00000-000'))
                        ->required(),

                    TextInput::make('logradouro')
                        ->label('Logradouro')
                        ->placeholder('Rua, Avenida, etc.')
                        ->required(),

                    TextInput::make('numero')
                        ->label('Número')
                        ->placeholder('Número do endereço')
                        ->required(),

                    TextInput::make('complemento')
                        ->label('Complemento')
                        ->placeholder('Apartamento, bloco, etc.')
                        ->nullable(),

                    TextInput::make('bairro')
                        ->label('Bairro')
                        ->required(),

                    TextInput::make('cidade')
                        ->label('Cidade')
                        ->required(),

                    TextInput::make('estado')
                        ->label('Estado')
                        ->placeholder('Ex: SP, RJ')
                        ->required(),
                ]),

            // Seção: Imagem do Perfil
            Section::make('Imagem do Perfil')
                ->schema([
                    FileUpload::make('imagem')
                        ->label('Foto do Profissional')
                        ->image()
                        ->directory('profissionais')
                        ->maxSize(2048) // 2MB
                        ->required()
                        ->helperText('Envie uma foto clara e de boa qualidade.'),
                ]),
        
            ]);
    }
}
