<?php

namespace App\Filament\Resources\PlanillaRecaudadorResource\Pages;

use App\Filament\Resources\PlanillaRecaudadorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanillaRecaudadors extends ListRecords
{
    protected static string $resource = PlanillaRecaudadorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
