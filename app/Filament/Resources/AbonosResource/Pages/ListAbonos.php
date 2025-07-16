<?php

namespace App\Filament\Resources\AbonosResource\Pages;

use App\Filament\Resources\AbonosResource;
use App\Models\Clientes;
use App\Models\Creditos;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class ListAbonos extends ListRecords
{
    protected static string $resource = AbonosResource::class;

    public ?int $clienteId = null;
    public ?string $fechaFiltro = null;

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
                    
                    // Verificar que el cliente tenga créditos activos
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

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->with(['cliente', 'credito'])
            ->when($this->clienteId, fn ($query) => $query->where('id_cliente', $this->clienteId))
            ->when($this->fechaFiltro, fn ($query) => $query->whereDate('fecha_pago', $this->fechaFiltro))
            ->orderBy('fecha_pago', 'desc');
    }

    protected function getHeader(): View
    {
        return view('filament.resources.abonos-resource.header', [
            'clientes' => Clientes::where('activo', true)->get()->pluck('nombre', 'id_cliente'),
            'clienteId' => $this->clienteId,
            'cliente' => $this->clienteId ? Clientes::with(['creditos', 'abonos'])->find($this->clienteId) : null,
            'fechaFiltro' => $this->fechaFiltro,
        ]);
    }
    public function updated($property)
    {
        if ($property === 'clienteId' || $property === 'fechaFiltro') {
            $this->resetPage();
        }
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}