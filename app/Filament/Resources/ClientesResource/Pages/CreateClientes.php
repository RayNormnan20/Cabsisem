<?php

namespace App\Filament\Resources\ClientesResource\Pages;

use App\Filament\Resources\ClientesResource;
use App\Models\LogActividad;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClientes extends CreateRecord
{
    protected static string $resource = ClientesResource::class;

    protected function afterCreate(): void
    {
        // Registrar la actividad en el log
       LogActividad::registrar(
            'Clientes', // El tipo de actividad
            'Registró un nuevo cliente' , // El mensaje descriptivo
            [ // ¡Este es el array 'metadata' y sigue siendo un array aquí!
                'cliente_id' => $this->record->id_cliente,
                'documento' => $this->record->numero_documento,
               'nombre_completo' => $this->record->nombre . ' ' . $this->record->apellido,
                //'negocio' => $this->record->nombre_negocio ?? 'Sin negocio'
            ]
            );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Cliente registrado exitosamente';
    }
}
