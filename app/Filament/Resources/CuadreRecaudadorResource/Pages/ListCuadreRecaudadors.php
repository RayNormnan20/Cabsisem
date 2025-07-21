<?php

namespace App\Filament\Resources\CuadreRecaudadorResource\Pages;

use App\Filament\Resources\CuadreRecaudadorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCuadreRecaudadors extends ListRecords
{
    protected static string $resource = CuadreRecaudadorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
