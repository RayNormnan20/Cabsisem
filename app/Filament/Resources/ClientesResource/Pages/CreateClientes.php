<?php

namespace App\Filament\Resources\ClientesResource\Pages;

use App\Filament\Resources\ClientesResource;
use App\Filament\Resources\CreditosResource;
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
            'Clientes',
            'Registró un nuevo cliente',
            [
                'cliente_id' => $this->record->id_cliente,
                'documento' => $this->record->numero_documento,
                'nombre_completo' => $this->record->nombre . ' ' . $this->record->apellido,
                'negocio' => $this->record->nombre_negocio ?? 'Sin negocio'
            ]
        );
    }

    protected function getRedirectUrl(): string
    {
        // Si el checkbox "crear_credito" está marcado, redirigir a crear crédito
        if ($this->record->crear_credito) {
            return CreditosResource::getUrl('create', [
                'cliente_id' => $this->record->id_cliente
            ]);
        }
        
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        if ($this->record->crear_credito) {
            return 'Cliente registrado. Redirigiendo a creación de crédito...';
        }
        
        return 'Cliente registrado exitosamente';
    }

    protected function getCreatedNotificationMessage(): ?string
    {
        if ($this->record->crear_credito) {
            return 'El cliente se ha registrado correctamente. Ahora puedes crear su crédito.';
        }
        
        return 'El cliente ha sido registrado en el sistema.';
    }
}