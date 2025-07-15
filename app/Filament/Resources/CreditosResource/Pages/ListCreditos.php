<?php

namespace App\Filament\Resources\CreditosResource\Pages;

use App\Filament\Resources\CreditosResource;
use App\Models\Clientes;
use App\Models\Creditos;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class ListCreditos extends ListRecords
{
    protected static string $resource = CreditosResource::class;

    public ?int $clienteId = null;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Mostramos solo los créditos del cliente seleccionado
    protected function getTableQuery(): Builder
    {
        return Creditos::query()
            ->when($this->clienteId, fn ($query) => $query->where('id_cliente', $this->clienteId))
            ->when(!$this->clienteId, fn ($query) => $query->whereRaw('1=0'));
    }

       protected function getHeader(): View
        {
            return view('filament.resources.creditos-resource.header', [
                'clientes' => Clientes::where('activo', true)->get()->pluck('nombre_completo', 'id_cliente'),
                'clienteId' => $this->clienteId,
                'cliente' => $this->clienteId ? Clientes::with('creditos')->find($this->clienteId) : null,
            ]);
        }



    public function updated($property)
    {
        if ($property === 'clienteId') {
            $this->clienteId = $this->clienteId ? (int) $this->clienteId : null;
            $this->resetPage();
        }
    }


    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    public function isTableVisible(): bool
    {
        return $this->clienteId !== null;
    }
}
