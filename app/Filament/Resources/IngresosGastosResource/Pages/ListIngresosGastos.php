<?php

namespace App\Filament\Resources\IngresosGastosResource\Pages;

use App\Filament\Resources\IngresosGastosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngresosGastos extends ListRecords
{
    protected static string $resource = IngresosGastosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
