<?php

namespace App\Filament\Resources\LogActividadResource\Pages;

use App\Filament\Resources\LogActividadResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogActividads extends ListRecords
{
    protected static string $resource = LogActividadResource::class;

    protected function getActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
