<?php

namespace App\Filament\Resources\OficinasResource\Pages;

use App\Filament\Resources\OficinasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOficinas extends EditRecord
{
    protected static string $resource = OficinasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
