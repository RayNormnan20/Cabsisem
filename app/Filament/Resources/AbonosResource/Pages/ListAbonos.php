<?php

namespace App\Filament\Resources\AbonosResource\Pages;

use App\Filament\Resources\AbonosResource;
use App\Models\Clientes;
use App\Models\Creditos;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class ListAbonos extends ListRecords
{
    protected static string $resource = AbonosResource::class;

    public ?int $clienteId = null;
    public ?string $fechaDesde = null;
    public ?string $fechaHasta = null;
    public string $periodoSeleccionado = 'hoy'; // Cambiado a 'hoy' por defecto

    protected $queryString = [
        'clienteId' => ['except' => null],
        'fechaDesde' => ['except' => null],
        'fechaHasta' => ['except' => null],
        'periodoSeleccionado' => ['except' => 'hoy'], // Cambiado a 'hoy' por defecto
    ];

    public function mount(): void
    {
        parent::mount();
        
        // Establecer fechas del día actual si no hay filtros aplicados
        if (is_null($this->fechaDesde)) {
            $this->aplicarPeriodo(); // Esto establecerá automáticamente el rango del día actual
        }
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Agregar Abono')
                ->button()
                ->color('primary')
                ->url(function () {
                    if (!$this->clienteId) {
                        return '#';
                    }
                    
                    $tieneCreditos = Creditos::where('id_cliente', $this->clienteId)
                        ->where('saldo_actual', '>', 0)
                        ->exists();
                    
                    if (!$tieneCreditos) {
                        $this->notify('warning', 'El cliente no tiene créditos activos');
                        return '#';
                    }
                    
                    return AbonosResource::getUrl('create', ['cliente_id' => $this->clienteId]);
                })
                ->visible($this->clienteId !== null),
        ];
    }

    public function aplicarPeriodo()
    {
        $hoy = Carbon::today();
        
        switch ($this->periodoSeleccionado) {
            case 'hoy':
                $this->fechaDesde = $hoy->format('Y-m-d');
                $this->fechaHasta = $hoy->format('Y-m-d');
                break;
            case 'ayer':
                $this->fechaDesde = $hoy->subDay()->format('Y-m-d');
                $this->fechaHasta = $this->fechaDesde;
                break;
            case 'semana_actual':
                $this->fechaDesde = $hoy->startOfWeek()->format('Y-m-d');
                $this->fechaHasta = $hoy->endOfWeek()->format('Y-m-d');
                break;
            case 'semana_anterior':
                $this->fechaDesde = $hoy->subWeek()->startOfWeek()->format('Y-m-d');
                $this->fechaHasta = $hoy->endOfWeek()->format('Y-m-d');
                break;
            case 'ultimas_2_semanas':
                $this->fechaDesde = $hoy->subWeeks(2)->format('Y-m-d');
                $this->fechaHasta = $hoy->format('Y-m-d');
                break;
            case 'mes_actual':
                $this->fechaDesde = $hoy->startOfMonth()->format('Y-m-d');
                $this->fechaHasta = $hoy->endOfMonth()->format('Y-m-d');
                break;
            case 'mes_anterior':
                $this->fechaDesde = $hoy->subMonth()->startOfMonth()->format('Y-m-d');
                $this->fechaHasta = $hoy->endOfMonth()->format('Y-m-d');
                break;
            default:
                // Para 'personalizado' no hacemos nada
                break;
        }
        
        $this->aplicarFiltroFecha();
    }

    public function aplicarFiltroFecha()
    {
        $this->resetPage();
    }

    public function resetFechas()
    {
        $this->fechaDesde = null;
        $this->fechaHasta = null;
        $this->periodoSeleccionado = 'hoy'; 
        $this->aplicarPeriodo();
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery()
            ->with(['cliente', 'credito', 'usuario']);
        
        // Solo filtrar por cliente si se ha seleccionado uno específico
            if (!empty($this->clienteId)) {
        $query->where('id_cliente', $this->clienteId); // Cambiado a id_cliente
    }
        
        // Aplicar filtros de fecha (se mantiene tu lógica original)
        if ($this->fechaDesde && $this->fechaHasta) {
            $query->whereDate('fecha_pago', '>=', $this->fechaDesde)
                ->whereDate('fecha_pago', '<=', $this->fechaHasta);
        } elseif ($this->fechaDesde) {
            $query->whereDate('fecha_pago', '>=', $this->fechaDesde);
        } elseif ($this->fechaHasta) {
            $query->whereDate('fecha_pago', '<=', $this->fechaHasta);
        } else {
            // Mostrar solo los del día actual cuando no hay fechas seleccionadas
            $hoy = Carbon::today()->format('Y-m-d');
            $query->whereDate('fecha_pago', $hoy);
        }
        
        return $query->orderBy('fecha_pago', 'desc');
    }

    protected function getHeader(): View
    {
        return view('filament.resources.abonos-resource.header', [
            'clientes' => Clientes::where('activo', true)->get()->pluck('nombre_completo', 'id_cliente'),
            'clienteId' => $this->clienteId,
            'cliente' => $this->clienteId ? Clientes::with(['creditos', 'abonos'])->find($this->clienteId) : null,
        ]);
    }

    public function updated($property)
    {
        if (in_array($property, ['clienteId', 'fechaDesde', 'fechaHasta', 'periodoSeleccionado'])) {
            $this->resetPage();
        }
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}