<?php

namespace App\Filament\Resources\ReportesResource\Pages;

use App\Filament\Resources\ReportesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportes extends ListRecords
{
    protected static string $resource = ReportesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
