<div class="flex flex-col space-y-4">
    <!-- Fila superior con selectores, filtros y botón -->
    <div class="flex items-center justify-between gap-4">
        <!-- Selector de cliente -->
        <div class="flex-1">
            <select 
                wire:model="clienteId" 
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            >
                <option value="">Seleccionar cliente</option>
                @foreach($clientes as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Contenedor para filtro y botón -->
        @if($clienteId)
            <div class="flex items-center space-x-4">
                <!-- Filtro de fecha -->
                <div class="flex items-center space-x-2">
                    <input 
                        type="date" 
                        wire:model="fechaFiltro"
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                    @if($fechaFiltro)
                        <button 
                            wire:click="$set('fechaFiltro', null)"
                            class="text-red-500 hover:text-red-700"
                        >
                            ×
                        </button>
                    @endif
                </div>

                <!-- Botón Crear Abono - Asegurando que pasa el cliente_id -->
                <a href="{{ route('filament.resources.abonos.create', ['cliente_id' => $clienteId]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                   wire:navigate>
                    Crear Abono
                </a>
            </div>
        @endif
    </div>
    <!-- Información del cliente seleccionado -->
    @if($clienteId)
        @php
            $cliente = \App\Models\Clientes::withCount(['creditos', 'abonos'])->find($clienteId);
        @endphp
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $cliente->nombre_completo }}</h3>
                    <p class="text-sm text-gray-600">DNI: {{ $cliente->numero_documento }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm">
                        <span class="font-medium">Créditos:</span> {{ $cliente->creditos_count }}
                    </p>
                    <p class="text-sm">
                        <span class="font-medium">Abonos:</span> {{ $cliente->abonos_count }}
                    </p>
                    <p class="text-sm">
                        <span class="font-medium">Estado:</span>
                        <span class="{{ $cliente->activo ? 'text-green-600' : 'text-red-600' }}">
                            {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>