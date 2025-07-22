<?php

namespace App\Filament\Resources\VistaMovimientoResource\Pages;

use App\Filament\Resources\VistaMovimientoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVistaMovimiento extends EditRecord
{
    protected static string $resource = VistaMovimientoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
