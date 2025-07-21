<?php

namespace App\Filament\Resources\CuadreRecaudadorResource\Pages;

use App\Filament\Resources\CuadreRecaudadorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuadreRecaudador extends EditRecord
{
    protected static string $resource = CuadreRecaudadorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
