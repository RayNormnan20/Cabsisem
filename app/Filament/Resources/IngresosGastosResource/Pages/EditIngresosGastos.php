<?php

namespace App\Filament\Resources\IngresosGastosResource\Pages;

use App\Filament\Resources\IngresosGastosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIngresosGastos extends EditRecord
{
    protected static string $resource = IngresosGastosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
