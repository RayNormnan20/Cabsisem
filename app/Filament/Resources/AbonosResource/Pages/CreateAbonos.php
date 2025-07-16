<?php

namespace App\Filament\Resources\AbonosResource\Pages;

use App\Filament\Resources\AbonosResource;
use App\Models\Clientes;
use App\Models\Creditos;
use App\Models\Ruta;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateAbonos extends CreateRecord
{
    protected static string $resource = AbonosResource::class;

    public $cliente_id;

    public function mount(): void
    {
        parent::mount();

        // Obtener el cliente_id de la URL
        $this->cliente_id = request()->query('cliente_id');

        // Si tenemos un cliente_id, precargar los datos
        if ($this->cliente_id) {
            $this->form->fill([
                'id_cliente' => $this->cliente_id,
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verificar que tenemos un cliente_id
        if (empty($data['id_cliente'])) {
            throw new \Exception('No se especificó un cliente para el abono');
        }

        // Buscar el crédito activo del cliente
        $credito = Creditos::where('id_cliente', $data['id_cliente'])
                        ->where('saldo_actual', '>', 0)
                        ->first();

        if (!$credito) {
            throw new \Exception('El cliente no tiene créditos activos');
        }

        // Obtener la ruta del usuario actual
        $id_ruta = $this->obtenerIdRutaUsuario();

        // Calcular los saldos
        $data['id_credito'] = $credito->id_credito;
        $data['id_ruta'] = $id_ruta; // Asignar la ruta del usuario
        $data['saldo_anterior'] = $credito->saldo_actual;
        $data['saldo_posterior'] = $credito->saldo_actual - ($data['monto_abono'] ?? 0);
        $data['id_usuario'] = auth()->id();
        $data['fecha_pago'] = now();

        // Actualizar el crédito con la ruta del usuario si es necesario
        if ($credito->id_ruta != $id_ruta) {
            $credito->id_ruta = $id_ruta;
            $credito->save();
        }

        return $data;
    }

    protected function obtenerIdRutaUsuario()
    {
        $usuario = auth()->user();

        // 1. Si es admin, obtener la primera ruta activa o lanzar error
        if ($usuario->hasRole('Administrador')) {
            $ruta = Ruta::activas()->first();
            if (!$ruta) {
                throw new \Exception('No hay rutas activas disponibles');
            }
            return $ruta->id_ruta;
        }

        // 2. Para cobradores, obtener su ruta asignada
       $rutaUsuario = $usuario->ruta()->first();

        if (!$rutaUsuario) {
            throw new \Exception('El usuario no tiene una ruta asignada');
        }

        return $rutaUsuario->id_ruta;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', [
            'cliente_id' => $this->record->id_cliente
        ]);
    }
}
