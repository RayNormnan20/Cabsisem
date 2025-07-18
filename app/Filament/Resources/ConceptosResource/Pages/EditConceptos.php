<?php

namespace App\Filament\Resources\ConceptosResource\Pages;

use App\Filament\Resources\ConceptosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConceptos extends EditRecord
{
    protected static string $resource = ConceptosResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }


    // Opcional: Mensaje de éxito personalizado
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Concepto actualizado correctamente';
    }
}