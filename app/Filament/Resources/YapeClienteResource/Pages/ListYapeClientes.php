<?php

namespace App\Filament\Resources\YapeClienteResource\Pages;

use App\Filament\Resources\YapeClienteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYapeClientes extends ListRecords
{
    protected static string $resource = YapeClienteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
