<?php

namespace App\Filament\Resources\RutasResource\Pages;

use App\Filament\Resources\RutasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRutas extends EditRecord
{
    protected static string $resource = RutasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
