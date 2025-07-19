<?php

namespace App\Filament\Resources\ClientesResource\Pages;

use App\Filament\Resources\ClientesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientes extends ListRecords
{
    protected static string $resource = ClientesResource::class;

    protected function getActions(): array
    {   
        return [
            Actions\CreateAction::make()
                ->label('Agregar Cliente')
                ->icon('heroicon-s-plus'),
        ];
    }
}