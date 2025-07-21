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
    <div class="bg-white rounded-lg shadow border border-gray-200 mb-6">
        {{-- Encabezado con nombre y botones --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">{{ $cliente->nombre_completo }}</h2>

            <div 
                x-data="{
                    open: false, 
                    showDeactivationModal: false,
                    deactivatingCreditId: null,

                    clientName: '',
                    capital: '',
                    interes: '',
                    saldo: '',
                    abonos: '',
                    saldoActual: '',

                    newValorCredito: '',
                    newInteres: '',
                    newFormaPago: '',
                    newCuenta: '',
                    newValorCuota: '',
                    newVencimientoDate: '',

                    init() {
                        this.$watch('newInteres', () => this.calcularFormaPagoYVencimiento());
                        this.$watch('newFormaPago', () => this.calcularFormaPagoYVencimiento());
                    },

                    setDeactivationCredit(creditId, creditData) {
                        console.log('data', creditData)
                        this.deactivatingCreditId = creditId;

                        this.clientName = creditData.clientName;
                        this.capital = creditData.capital;

                        this.interes = (creditData.capital * (creditData.interes / 100)).toFixed(2);
                        this.saldo = (creditData.capital + parseFloat(this.interes)).toFixed(2);

                        let totalAbonos = 0;
                        if (Array.isArray(creditData.abonos) && creditData.abonos.length > 0) {
                            totalAbonos = creditData.abonos.reduce(
                                (sum, abono) => sum + parseFloat(abono.monto_abono),
                                0
                            );
                            this.abonos = totalAbonos.toFixed(2);
                        } else {
                            this.abonos = 'No hay abonos por el momento';
                        }

                        const totalAdeudado = creditData.capital + parseFloat(this.interes);
                        this.saldoActual = (totalAdeudado - totalAbonos).toFixed(2);

                        this.newValorCredito = this.saldoActual;
                        this.newInteres = creditData.interes;
                        this.newFormaPago = '';
                        this.newCuenta = '';
                        this.newValorCuota = '';
                        this.newVencimientoDate = creditData.fechaVencimiento || '';

                        this.showDeactivationModal = true;
                        this.open = false;
                    },

                    calcularFormaPagoYVencimiento() {
                        const capital = parseFloat(this.newValorCredito);
                        const interes = parseFloat(this.newInteres);
                        const diasPago = parseInt(this.newFormaPago);

                        if (isNaN(capital) || isNaN(interes) || isNaN(diasPago) || diasPago <= 0) {
                            this.newCuenta = '';
                            this.newValorCuota = '';
                            this.newVencimientoDate = '';
                            return;
                        }

                        const meses = Math.ceil(diasPago / 30);
                        const interesMensual = capital * (interes / 100);
                        const interesTotal = interesMensual * meses;

                        const totalPagar = capital + interesTotal;
                        const cuotaDiaria = totalPagar / diasPago;
                        this.newValorCuota = cuotaDiaria.toFixed(2);

                        this.newCuenta = totalPagar.toFixed(2);

                        const hoy = new Date();
                        hoy.setDate(hoy.getDate() + diasPago);
                        this.newVencimientoDate = hoy.toISOString().split('T')[0];
                    },

                    guardarDatosCredito() {
                        fetch('/creditos/actualizar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                            },
                            body: JSON.stringify({
                                credito_id: this.deactivatingCreditId,
                                nuevo_interes: parseFloat(this.newInteres),
                                forma_pago: parseInt(this.newFormaPago),
                                nueva_cuenta: parseFloat(this.newCuenta),
                                valor_cuota: parseFloat(this.newValorCuota),
                                fecha_vencimiento: this.newVencimientoDate,
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.message) {
                                alert(data.message);
                                this.showDeactivationModal = false;
                            } else {
                                alert(data.error || 'Error al actualizar');
                            }
                        })
                        .catch(e => {
                            console.error(e);
                            alert('Error de conexión.');
                        });
                    }
                }"
                x-init="init()"
                class="flex items-center space-x-2">
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

                    <div class="relative inline-block text-left z-20">
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

                                <!-- <a href="#"
                                    @click.prevent="setDeactivationCredit({{ $cliente->creditos->first()->id_credito }})"
                                    class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                    role="menuitem" tabindex="-1" id="menu-item-1">
                                    Baja de Cuenta
                                </a> -->
                                <a href="#"
                                    @click.prevent="setDeactivationCredit(
                                        {{ $cliente->creditos->first()->id_credito }},
                                        { // Pasamos un objeto con los datos del crédito para mostrar en el modal
                                            clientName: '{{ $cliente->nombre_completo ?? $cliente->nombre }}', // Ajusta según el campo de nombre de tu cliente
                                            capital: {{ $cliente->creditos->first()->valor_credito ?? 0 }},
                                            interes: {{ $cliente->creditos->first()->porcentaje_interes ?? 0 }},
                                            saldo: {{ $cliente->creditos->first()->saldo_actual ?? 0 }},
                                            abonos: {{ $cliente->creditos->first()->abonos ?? 0 }},
                                            
                                            fechaVencimiento: '{{ $cliente->creditos->first()->fecha_vencimiento ?? '' }}' // Ajusta el campo de fecha de vencimiento
                                        }
                                    )"
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

                {{-- Modal de Confirmación para Baja de Cuenta --}}
                <div x-show="showDeactivationModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        {{-- Fondo del overlay --}}
                        <div x-show="showDeactivationModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        {{-- Este span es para centrar el contenido del modal horizontalmente --}}
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        {{-- Panel del modal --}}
                        <div x-show="showDeactivationModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full"> {{-- Aumentado el ancho a sm:max-w-3xl --}}
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.26-3.168 1.26-4.034 0L.436 4.673A1.875 1.875 0 012.007 2.25h14.536a1.875 1.875 0 011.571 2.423L12 9v3.75m-9.303 3.376c-.866 1.26-3.168 1.26-4.034 0L.436 4.673A1.875 1.875 0 012.007 2.25h14.536a1.875 1.875 0 011.571 2.423L12 9v3.75M10.125 15.75L12 21.75l-1.875-6zm-.825-4.725L12 11.25m0 0l-1.875-6z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Bajar Cuenta
                                        </h3>
                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {{-- Columna Izquierda: Datos del Crédito (Solo lectura) --}}
                                            <div>
                                                <h4 class="text-md font-semibold text-gray-800 mb-2">Datos Actuales</h4>

                                                <div class="mb-3">
                                                    <label for="cliente-display" class="block text-sm font-medium text-gray-700">Cliente</label>
                                                    <input type="text" id="cliente-display" x-model="clientName" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="capital-display" class="block text-sm font-medium text-gray-700">Capital</label>
                                                    <input type="text" id="capital-display" x-model="capital" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="interes-display" class="block text-sm font-medium text-gray-700">Interés</label>
                                                    <input type="text" id="interes-display" x-model="interes" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="saldo-display" class="block text-sm font-medium text-gray-700">Saldo</label>
                                                    <input type="text" id="saldo-display" x-model="saldo" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="abonos-display" class="block text-sm font-medium text-gray-700">Abonos</label>
                                                    <template x-if="abonos === 'No hay abonos por el momento'">
                                                        <input type="text" value="No hay abonos por el momento" disabled
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-red-600 italic" />
                                                    </template>
                                                    <template x-if="abonos !== 'No hay abonos por el momento'">
                                                        <input type="text" x-model="abonos" disabled
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600" />
                                                    </template>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="saldo-actual-display" class="block text-sm font-medium text-gray-700">Saldo Actual</label>
                                                    <input type="text" id="saldo-actual-display" x-model="saldoActual" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                            </div>

                                            {{-- Columna Derecha: Campos a Editar --}}
                                            <div>
                                                <h4 class="text-md font-semibold text-gray-800 mb-2">Nuevo Cálculo</h4>

                                                <div class="mb-3">
                                                    <label for="valor-credito" class="block text-sm font-medium text-gray-700">Valor Crédito</label>
                                                    <input type="number" id="valor-credito" x-model="newValorCredito" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nuevo-interes" class="block text-sm font-medium text-gray-700">Nuevo Interés</label>
                                                    <input type="number" step="0.01" id="nuevo-interes" x-model="newInteres"  @input="calcularFormaPagoYVencimiento()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="forma-pago" class="block text-sm font-medium text-gray-700">Forma de Pago (Días)</label>
                                                    <input type="number" step="0.01" id="nuevo-forma-pago" x-model="newFormaPago" @input="calcularFormaPagoYVencimiento()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nueva-cuenta" class="block text-sm font-medium text-gray-700">Nueva Cuenta</label>
                                                    <input type="text" id="nueva-cuenta" x-model="newCuenta" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="fecha-actual" class="block text-sm font-medium text-gray-700">Fecha Actual</label>
                                                    <input type="date" id="fecha-actual" :value="new Date().toISOString().split('T')[0]" disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nueva-fecha-vencimiento" class="block text-sm font-medium text-gray-700">Nueva Fecha de Vencimiento</label>
                                                    <input type="date" id="nueva-fecha-vencimiento" x-model="newVencimientoDate" disabled readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" @click="guardarDatosCredito()"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Confirmar Actualización
                                </button>
                                <button type="button" @click="showDeactivationModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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