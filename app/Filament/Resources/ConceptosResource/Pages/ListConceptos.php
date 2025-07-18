<?php

namespace App\Filament\Resources\ConceptosResource\Pages;

use App\Filament\Resources\ConceptosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConceptos extends ListRecords
{
    protected static string $resource = ConceptosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Agregar Concepto')
                ->icon('heroicon-s-plus'),
        ];
    }
}
