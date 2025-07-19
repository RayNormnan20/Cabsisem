<div class="mb-6 bg-white p-4 rounded-lg shadow">
    <label for="cliente-selector" class="block text-sm font-medium text-gray-700 mb-2">
        Seleccionar Cliente
    </label>
    
    <select
        id="cliente-selector"
        wire:model="cliente_id"
        wire:change="cargarDatosCliente($event.target.value)"
        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
    >
        <option value="">-- Seleccione un cliente --</option>
        @foreach($clientes as $cliente)
            <option value="{{ $cliente->id_cliente }}" {{ $cliente_id == $cliente->id_cliente ? 'selected' : '' }}>
                {{ $cliente->nombre_completo }}
            </option>
        @endforeach
    </select>
</div>