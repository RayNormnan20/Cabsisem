<x-filament::card>
    <!-- Contenedor principal que ocupa todo el ancho -->
    <div class="w-full space-y-6">

        <!-- Primera fila: Cards con valores -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
            <!-- Card CUA Actual -->
            <div class="p-4 bg-white rounded-lg shadow w-full">
                <h3 class="text-lg font-semibold text-gray-700">CUA ACTUAL</h3>
                <p class="text-2xl font-bold {{ $cuaActual < 0 ? 'text-red-500' : 'text-green-500' }}">
                    {{ $cuaActual }}
                </p>
            </div>

            <!-- Card CUA Anterior -->
            <div class="p-4 bg-white rounded-lg shadow w-full">
                <h3 class="text-lg font-semibold text-gray-700">CUA ANTERIOR</h3>
                <p class="text-2xl font-bold {{ $cuaAnterior < 0 ? 'text-red-500' : 'text-green-500' }}">
                    {{ $cuaAnterior }}
                </p>
            </div>

            <!-- Card Clientes PARADIGM -->
            <div class="p-4 bg-white rounded-lg shadow w-full">
                <h3 class="text-lg font-semibold text-gray-700">CLIENTES</h3>
                <p class="text-2xl font-bold text-blue-500">
                    {{ $totalClientes }}
                </p>
            </div>
        </div>

        <!-- Segunda fila: Barra de ingresos/gastos
        <div class="w-full bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">INGRESOS Y GASTOS</h3>
            <div class="grid grid-cols-5 gap-4 w-full">
                @foreach($ingresosGastos as $monto)
                <div class="bg-primary-500 text-white p-3 rounded-lg text-center">
                    <span class="font-bold text-lg">{{ $monto }}</span>
                </div>
                @endforeach
            </div>
        </div>
-->
        <!-- Tercera fila: Secciones adicionales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
            <!-- Botón de CLIENTES mejorado -->
            <div class="bg-white p-6 rounded-xl shadow-lg cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border-l-4 border-primary-500"
                onclick="window.location.href='{{ route('filament.resources.clientes.index') }}'">
                <div class="text-center">
                    <div
                        class="bg-primary-100 p-3 rounded-full w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <x-heroicon-o-users class="w-8 h-8 text-primary-600" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">CLIENTES</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ App\Models\Clientes::count() }} registrados</p>
                </div>
            </div>

            <!-- Botón de ABONOS mejorado -->
            <div class="bg-white p-6 rounded-xl shadow-lg cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border-l-4 border-green-500"
                onclick="window.location.href='{{ route('filament.resources.abonos.index') }}'">
                <div class="text-center">
                    <div class="bg-green-100 p-3 rounded-full w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <x-heroicon-o-cash class="w-8 h-8 text-green-600" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">ABONOS</h3>
                    <p class="text-sm text-gray-500 mt-1">Últimos pagos</p>
                </div>
            </div>

            <!-- Botón de CRÉDITOS mejorado -->
            <div class="bg-white p-6 rounded-xl shadow-lg cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border-l-4 border-indigo-500"
                onclick="window.location.href='{{ route('filament.resources.creditos.index') }}'">
                <div class="text-center">
                    <div class="bg-indigo-100 p-3 rounded-full w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <x-heroicon-o-office-building class="w-8 h-8 text-indigo-600" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">CRÉDITOS</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ App\Models\Creditos::count() }} activos</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border-l-4 border-primary-500"
                onclick="window.location.href='{{ route('filament.resources.clientes.index') }}'">
                <div class="text-center">
                    <div
                        class="bg-primary-100 p-3 rounded-full w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <x-heroicon-o-users class="w-8 h-8 text-primary-600" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">CLIENTES</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ App\Models\Clientes::count() }} registrados</p>
                </div>
            </div>
        </div>

    </div>
</x-filament::card>
