<?php

namespace App\Filament\Resources\CreditosResource\Pages;

use App\Filament\Resources\CreditosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditos extends ListRecords
{
    protected static string $resource = CreditosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
