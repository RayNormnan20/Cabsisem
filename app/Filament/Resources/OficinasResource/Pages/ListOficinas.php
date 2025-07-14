<?php

namespace App\Filament\Resources\OficinasResource\Pages;

use App\Filament\Resources\OficinasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOficinas extends ListRecords
{
    protected static string $resource = OficinasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Oficina')
                ->icon('heroicon-o-plus')
                ->button(),
        ];
    }
}
