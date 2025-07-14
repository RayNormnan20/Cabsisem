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
            Actions\ViewAction::make()
                ->icon('heroicon-o-eye')
                ->button(),

            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->button(),

            Actions\Action::make('back')
                ->label('Volver')
                ->url(static::$resource::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
                ->color('secondary')
                ->button(),
        ];
    }
}
