<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Creditos;
use Illuminate\Http\Request;

class CreditoController extends Controller
{

    public function actualizarDatosCredito(Request $request)
    {
        try {
            $request->validate([
                'credito_id' => 'required|exists:creditos,id_credito',
                'nuevo_interes' => 'required|numeric',
                'forma_pago' => 'required|integer',
                'nueva_cuenta' => 'required|numeric',
                'valor_cuota' =>'required|numeric',
                'fecha_vencimiento' => 'required|date',
            ]);

            $credito = Creditos::find($request->credito_id);

            if (!$credito) {
                return response()->json(['error' => 'Crédito no encontrado'], 404);
            }

            $credito->porcentaje_interes = $request->nuevo_interes;
            $credito->dias_plazo = $request->forma_pago;
            $credito->valor_cuota = $request->valor_cuota;
            $credito->saldo_actual = $request->nueva_cuenta;
            $credito->fecha_vencimiento = $request->fecha_vencimiento;

            $credito->save();

            return response()->json([
                'message' => 'Datos del crédito actualizados correctamente',
                'credito' => $credito
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al actualizar crédito',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
