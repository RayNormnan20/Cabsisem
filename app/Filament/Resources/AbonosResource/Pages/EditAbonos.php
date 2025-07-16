<?php

namespace App\Filament\Resources\AbonosResource\Pages;

use App\Filament\Resources\AbonosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbonos extends EditRecord
{
    protected static string $resource = AbonosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
