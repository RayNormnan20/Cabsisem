@php
$clienteIds = array_keys($clientes->toArray());
$currentIndex = array_search($clienteId, $clienteIds);
$anteriorId = $currentIndex > 0 ? $clienteIds[$currentIndex - 1] : null;
$siguienteId = $currentIndex < count($clienteIds) - 1 ? $clienteIds[$currentIndex + 1] : null; @endphp
    {{-- SELECTOR DE CLIENTE CON NAVEGACIÓN --}} <div class="mb-6 flex items-center justify-center gap-4">
    {{-- Botón anterior --}}
    <button wire:click="$set('clienteId', {{ $anteriorId ?? 'null' }})"
        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-sm rounded-md disabled:opacity-50"
        @disabled($anteriorId===null)>
        ◀
    </button>

    {{-- Select en el centro más pequeño --}}
    <div class="w-1/2">
        <label for="clienteId" class="block text-base font-semibold text-gray-700 mb-1 text-center">
            Seleccionar Cliente
        </label>
        <select wire:model="clienteId" id="clienteId"
            class="w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 text-sm focus:ring-primary-500 focus:border-primary-500">
            <option value="">-- Seleccionar --</option>
            @foreach ($clientes as $id => $nombre)
            <option value="{{ $id }}">{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    {{-- Botón siguiente --}}
    <button wire:click="$set('clienteId', {{ $siguienteId ?? 'null' }})"
        class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-sm rounded-md disabled:opacity-50"
        @disabled($siguienteId===null)>
        ▶
    </button>
    </div>


    @if($cliente)
    @php
    // Asegurarnos de cargar la relación creditos si no está cargada
    $cliente->loadMissing('creditos');
    @endphp

    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 mb-6">
        {{-- Encabezado con nombre y botones --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">{{ $cliente->nombre_completo }}</h2>

            <div class="flex space-x-2">
                {{-- Botón Editar Cliente --}}
                <a href="{{ route('filament.resources.clientes.edit', ['record' => $cliente->id_cliente]) }}"
                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Editar Cliente
                </a>

                {{-- Botón Crédito --}}
                @if($cliente->creditos->isNotEmpty())
                <a href="{{ route('filament.resources.creditos.edit', ['record' => $cliente->creditos->first()->id_credito]) }}"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Editar Crédito
                </a>
                @else
                <a href="{{ route('filament.resources.creditos.create', ['cliente_id' => $cliente->id_cliente]) }}"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Crear Crédito
                </a>
                @endif
            </div>
        </div>

        {{-- Información desplegable --}}
        <div x-data="{ open: false }" class="px-6 py-4">
            <button @click="open = !open" type="button"
                class="text-primary-600 hover:text-primary-900 text-sm font-medium flex items-center focus:outline-none">
                <span x-text="open ? '▲ Ocultar información' : '▼ Ver información del cliente'"></span>
            </button>

            <div x-show="open" x-transition class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium">Documento:</span> {{ $cliente->numero_documento }}</div>
                <div><span class="font-medium">Celular:</span> {{ $cliente->celular }}</div>
                <div><span class="font-medium">Teléfono:</span> {{ $cliente->telefono }}</div>
                <div><span class="font-medium">Dirección:</span> {{ $cliente->direccion }}</div>
                <div><span class="font-medium">Negocio/Alias:</span> {{ $cliente->nombre_negocio }}</div>
                <div><span class="font-medium">Ciudad:</span> {{ $cliente->ciudad }}</div>
                <div>
                    <span class="font-medium">Status:</span>
                    <span class="{{ $cliente->activo ? 'text-green-600' : 'text-red-600' }}">
                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif
