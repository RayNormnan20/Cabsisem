<?php

namespace App\Filament\Resources\ConceptosResource\Pages;

use App\Filament\Resources\ConceptosResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateConceptos extends CreateRecord
{
    protected static string $resource = ConceptosResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('cancel')
                ->label('Volver')
                ->url(static::getResource()::getUrl('index'))
                ->color('secondary')
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); 
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Concepto creado exitosamente'; 
    }
}