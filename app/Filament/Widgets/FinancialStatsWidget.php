<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Clientes;

class FinancialStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.financial-stats-widget';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'cuaActual' => '-439,856',
            'cuaAnterior' => '-442,121',
            'ingresosGastos' => [6000, 3000, 2000, 3000, 2000],
            'totalClientes' => Clientes::count(),

        ];
    }
}