<?php

namespace App\Filament\Resources\RutasResource\Pages;

use App\Filament\Resources\RutasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRutas extends EditRecord
{
    protected static string $resource = RutasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Ruta actualizada exitosamente';
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Eliminar Ruta')
                ->icon('heroicon-s-trash'),

            Actions\Action::make('ver_listado')
                ->label('Ver Listado')
                ->url($this->getResource()::getUrl('index'))
                ->color('secondary')
                ->icon('heroicon-s-menu-alt-2')
        ];
    }
}