<div class="mb-6 bg-white p-4 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Ingresos y Gastos</h2>

    <!-- Contenedor de selectores -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Selector Desde -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
            <input type="date" wire:model="fechaDesde" wire:change="validarFechas" max="{{ $fechaHasta }}"
                class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            @error('fechaDesde')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Selector Hasta -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
            <input type="date" wire:model="fechaHasta" wire:change="validarFechas" min="{{ $fechaDesde }}"
                class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            @error('fechaHasta')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>


</div>
