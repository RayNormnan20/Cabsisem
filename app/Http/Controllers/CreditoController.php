<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ConceptoCredito;
use App\Models\Creditos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /* public function renovar(Request $request)
    {
        Log::info('Llamada al método renovar');

        $data = $request->json()->all(); // ✅ Lee el JSON enviado por fetch
        Log::info('Datos recibidos:', $data);

        DB::beginTransaction();

        try {
            // Buscar el crédito original
            $credito = Creditos::findOrFail($data['id']);

            // Actualizar datos del crédito
            $credito->update([
                'valor_credito'       => $data['valor_credito'],
                'porcentaje_interes'  => $data['porcentaje_interes'],
                'saldo_actual'        => $data['valor_credito'],
                'forma_pago'          => null,
                'dias_plazo'          => $data['dias_plazo'],
                'fecha_credito'       => now(),
                'fecha_vencimiento'   => $data['fecha_vencimiento'],
                'valor_cuota'         => $data['valor_cuota'] ?? 0,
                'numero_cuotas'       => $data['numero_cuotas'] ?? 0,
            ]);

            // Insertar métodos de pago en conceptos_credito
            foreach ($data['medios_pago'] as $medio) {
                ConceptoCredito::create([
                    'id_credito'     => $credito->id_credito,
                    'tipo_concepto'  => $medio['tipo'],   // ejemplo: 'Yape', 'Efectivo'
                    'monto'          => $medio['monto'],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Crédito renovado correctamente.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al renovar crédito: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al renovar crédito.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    } */

    public function renovar(Request $request)
    {
        try {
            // Buscar el crédito original
            $credito = Creditos::findOrFail($request->id);

            // Actualizar solo los campos permitidos (excluyendo forma_pago)
            $credito->valor_credito = $request->valor_credito;
            $credito->saldo_actual = $request->valor_credito;
            /* $credito->valor_cuota = $request->valor_cuota; */
            $credito->numero_cuotas = $request->numero_cuotas;
            $credito->dias_plazo = $request->dias_plazo;
            $credito->porcentaje_interes = $request->porcentaje_interes;
            $credito->fecha_vencimiento = $request->fecha_vencimiento;
            $credito->fecha_credito = now(); // o el valor que corresponda

            // Calcular días restantes
            $fechaHoy        = Carbon::now();
            $fechaVencimiento = Carbon::parse($request->fecha_vencimiento);
            $diasRestantes    = $fechaHoy->diffInDays($fechaVencimiento);

            // Validar para evitar división por cero
            if ($diasRestantes <= 0) {
                return response()->json([
                    'message' => 'La fecha de vencimiento debe ser posterior a hoy.',
                ], 400);
            }

            $credito->numero_cuotas = $diasRestantes;

            // Calcular cuota diaria
            $cuotaDiaria = round($request->valor_credito / $diasRestantes, 2);
            $credito->valor_cuota = $cuotaDiaria;

            // Guardar los cambios
            $credito->save();

            // Guardar los medios de pago nuevos
            if ($request->has('medios_pago') && is_array($request->medios_pago)) {
                foreach ($request->medios_pago as $mp) {
                    $credito->conceptosCredito()->create([
                        'tipo_concepto' => $mp['tipo'],
                        'monto'         => $mp['monto'],
                    ]);
                }
            }

            return response()->json([
                'message' => 'Crédito renovado correctamente.',
                'cuota_diaria_calculada' => $cuotaDiaria,
                'dias_restantes' => $diasRestantes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al renovar crédito.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
