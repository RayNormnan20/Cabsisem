<?php

namespace App\Filament\Resources\AbonosResource\Pages;

use App\Filament\Resources\AbonosResource;
use App\Models\Concepto;
use App\Models\Creditos;
use App\Models\Ruta;
use COM;
use Filament\Resources\Pages\CreateRecord;

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

        $id_ruta = $this->obtenerIdRutaUsuario();
        $montoAbono = $data['monto_abono'] ?? 0;

        // Obtener el concepto "Abono" de la tabla conceptos
        $conceptoAbono = Concepto::where('nombre', 'Abono')->first();

        if (!$conceptoAbono) {
            throw new \Exception('El concepto "Abono" no existe en la tabla de conceptos');
        }

        // Asignar el id_concepto del abono
        $data['id_concepto'] = $conceptoAbono->id;

        // Calcular los saldos
        $data['id_credito'] = $credito->id_credito;
        $data['id_ruta'] = $id_ruta;
        $data['saldo_anterior'] = $credito->saldo_actual;
        $data['saldo_posterior'] = $credito->saldo_actual - $montoAbono;
        $data['id_usuario'] = auth()->id();
        $data['fecha_pago'] = now();

        // Actualizar el crédito con el nuevo saldo y ruta
        $credito->saldo_actual = $data['saldo_posterior'];

        if ($credito->id_ruta != $id_ruta) {
            $credito->id_ruta = $id_ruta;
        }

        $credito->save();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Confirmar que el crédito se actualizó correctamente
        $credito = Creditos::find($this->record->id_credito);
        if ($credito) {
            $credito->saldo_actual = $this->record->saldo_posterior;
            $credito->save();
        }
    }

    protected function obtenerIdRutaUsuario()
    {
        $usuario = auth()->user();

        if ($usuario->hasRole('Administrador')) {
            $ruta = Ruta::activas()->first();
            if (!$ruta) {
                throw new \Exception('No hay rutas activas disponibles');
            }
            return $ruta->id_ruta;
        }

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
