<?php

namespace App\Filament\Resources\VistaMovimientoResource\Pages;

use App\Filament\Resources\VistaMovimientoResource;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class ListVistaMovimientos extends ListRecords
{
    protected static string $resource = VistaMovimientoResource::class;

    public ?string $fechaDesde = null;
    public ?string $fechaHasta = null;
    public bool $fechasValidas = true;

    public function mount(): void
    {
        parent::mount();
        $this->fechaDesde = Carbon::today()->subMonth()->toDateString();
        $this->fechaHasta = Carbon::today()->toDateString();
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('+ Agregar Ingreso o Gasto'),
        ];
    }

    protected function getHeader(): View
    {
        return view('filament.resources.vista-movimiento-resource.header', [
            'fechaDesde' => $this->fechaDesde,
            'fechaHasta' => $this->fechaHasta,
            'fechasValidas' => $this->fechasValidas
        ]);
    }

    protected function getTableQuery(): Builder
    {
        $this->validarFechas();

        if (!$this->fechasValidas) {
            return parent::getTableQuery()->whereRaw('1=0');
        }

        return parent::getTableQuery()
            ->when($this->fechaDesde, function (Builder $query) {
                return $query->whereDate('fecha', '>=', $this->fechaDesde);
            })
            ->when($this->fechaHasta, function (Builder $query) {
                return $query->whereDate('fecha', '<=', $this->fechaHasta);
            });
    }

    public function validarFechas()
    {
        $this->fechasValidas = true;

        if ($this->fechaDesde || $this->fechaHasta) {
            try {
                $this->validate([
                    'fechaDesde' => 'required_with:fechaHasta|date|before_or_equal:fechaHasta',
                    'fechaHasta' => 'required_with:fechaDesde|date|after_or_equal:fechaDesde'
                ], [
                    'fechaDesde.required_with' => 'La fecha Desde es requerida cuando se especifica Hasta',
                    'fechaHasta.required_with' => 'La fecha Hasta es requerida cuando se especifica Desde',
                    'fechaDesde.before_or_equal' => 'La fecha Desde debe ser anterior o igual a la fecha Hasta',
                    'fechaHasta.after_or_equal' => 'La fecha Hasta debe ser posterior o igual a la fecha Desde'
                ]);
            } catch (ValidationException $e) {
                $this->fechasValidas = false;
                throw $e;
            }
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['fechaDesde', 'fechaHasta']);
        $this->fechasValidas = true;
        $this->resetPage();
    }
}
