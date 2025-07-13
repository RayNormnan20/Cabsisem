<?php

namespace App\Http\Controllers;

use App\Models\TipoFinanciamiento;
use Illuminate\Http\Request;

class TipoFinanciamientoController extends Controller
{
    /**
     * Muestra una lista de los tipos de financiamiento.
     */
    public function index()
    {
        $tipos = TipoFinanciamiento::all();
        return view('tipos_financiamiento.index', compact('tipos'));
    }

    /**
     * Muestra el formulario para crear un nuevo tipo de financiamiento.
     */
    public function create()
    {
        return view('tipos_financiamiento.create');
    }

    /**
     * Almacena un nuevo tipo de financiamiento en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_financiamiento,nombre',
            'descripcion' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7', // Asegura un valor de color válido (hexadecimal)
            'is_default' => 'nullable|boolean',
        ]);

        TipoFinanciamiento::create($validated);

        return redirect()->route('tipos-financiamiento.index')
                         ->with('success', 'Tipo de financiamiento creado exitosamente.');
    }

    /**
     * Muestra un tipo de financiamiento específico.
     */
    public function show($id)
    {
        $tipo = TipoFinanciamiento::findOrFail($id);
        return view('tipos_financiamiento.show', compact('tipo'));
    }

    /**
     * Muestra el formulario para editar un tipo de financiamiento existente.
     */
    public function edit($id)
    {
        $tipo = TipoFinanciamiento::findOrFail($id);
        return view('tipos_financiamiento.edit', compact('tipo'));
    }

    /**
     * Actualiza un tipo de financiamiento en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $tipo = TipoFinanciamiento::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_financiamiento,nombre,' . $tipo->id,
            'descripcion' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'is_default' => 'nullable|boolean',
        ]);

        $tipo->update($validated);

        return redirect()->route('tipos-financiamiento.index')
                         ->with('success', 'Tipo de financiamiento actualizado exitosamente.');
    }

    /**
     * Elimina un tipo de financiamiento de la base de datos.
     */
    public function destroy($id)
    {
        $tipo = TipoFinanciamiento::findOrFail($id);
        $tipo->delete();

        return redirect()->route('tipos-financiamiento.index')
                         ->with('success', 'Tipo de financiamiento eliminado exitosamente.');
    }
}
