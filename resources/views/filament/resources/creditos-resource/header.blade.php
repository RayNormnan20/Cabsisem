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

    {{-- ¡IMPORTANTE! Se ha eliminado 'overflow-hidden' de este div --}}
    <div x-data="{ showBajaModal: false }" class="bg-white rounded-lg shadow border border-gray-200 mb-6">
        {{-- Encabezado con nombre y botones --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">{{ $cliente->nombre_completo }}</h2>

            {{-- Contenedor de los botones de acción (Editar Cliente, Editar Crédito/Crear Crédito, y el Dropdown) --}}
            <div class="flex items-center space-x-2">
                {{-- Botón Editar Cliente --}}
                <a href="{{ route('filament.resources.clientes.edit', ['record' => $cliente->id_cliente]) }}"
                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Editar Cliente
                </a>

                @if($cliente->creditos->isNotEmpty())
                {{-- Grupo para el botón Editar Crédito y el Dropdown de Acciones --}}
                <div class="flex items-center space-x-2">
                    <a href="{{ route('filament.resources.creditos.edit', ['record' => $cliente->creditos->first()->id_credito]) }}"
                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Editar Crédito
                    </a>

                    <!-- Contenedor del Dropdown de Acciones -->
                    <div x-data="{ open: false }" class="relative inline-block text-left z-20">
                        <div>
                            <button type="button" @click="open = !open"
                                class="inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                id="menu-button" aria-expanded="true" aria-haspopup="true">
                                Acciones
                                <svg class="-mr-1 ml-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- Panel del Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-30"
                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <a href="{{ route('filament.resources.creditos.create', ['cliente_id' => $cliente->id_cliente]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Nuevo Préstamo</a>
                                <a href="{{ route('filament.resources.creditos.edit', ['record' => $cliente->creditos->first()->id_credito]) }}"
                                    class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                    role="menuitem" tabindex="-1" id="menu-item-1">
                                    Baja de Cuenta
                                </a>
                                <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-2">Cancelado</a>
                                <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-3">Renovación</a>
                            </div>
                        </div>
                    </div>
                </div>
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