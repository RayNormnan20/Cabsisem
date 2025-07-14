<?php

namespace App\Filament\Resources\RutasResource\Pages;

use App\Filament\Resources\RutasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRutas extends CreateRecord
{
    protected static string $resource = RutasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ruta creada exitosamente';
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl('index'))
                ->color('secondary'),
        ];
    }
}
