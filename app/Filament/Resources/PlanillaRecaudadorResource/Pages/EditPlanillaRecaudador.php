<?php

namespace App\Filament\Resources\PlanillaRecaudadorResource\Pages;

use App\Filament\Resources\PlanillaRecaudadorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanillaRecaudador extends EditRecord
{
    protected static string $resource = PlanillaRecaudadorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
