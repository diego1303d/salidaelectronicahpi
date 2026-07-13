<x-layouts.app title="Entradas">

    <x-page-header
        title="Entradas de inventario"
        subtitle="Ingresos de trigo a las bodegas" />

    {{-- Mensajes flash --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-sm text-emerald-800
                    dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700
                    dark:border-red-800 dark:bg-red-950/50 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtros y acciones --}}
<form method="GET" action="{{ route('entradas.index') }}"
      class="mb-4 flex flex-wrap items-end gap-3">

    {{-- Bodega --}}
    <div>
        <label for="ubicacion_id" class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
            Bodega
        </label>
        <select id="ubicacion_id" name="ubicacion_id"
                class="rounded-lg border-gray-300 text-sm
                       focus:border-emerald-600 focus:ring-emerald-600
                       dark:border-gray-600 dark:bg-gray-800">
            <option value="">Todas</option>
            @foreach ($ubicaciones as $ubicacion)
                <option value="{{ $ubicacion->id }}" @selected(request('ubicacion_id') == $ubicacion->id)>
                    {{ $ubicacion->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Desde --}}
    <div>
        <label for="desde" class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
            Desde
        </label>
        <input type="date" id="desde" name="desde" value="{{ request('desde') }}"
               class="rounded-lg border-gray-300 text-sm
                      focus:border-emerald-600 focus:ring-emerald-600
                      dark:border-gray-600 dark:bg-gray-800">
    </div>

    {{-- Hasta --}}
    <div>
        <label for="hasta" class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
            Hasta
        </label>
        <input type="date" id="hasta" name="hasta" value="{{ request('hasta') }}"
               class="rounded-lg border-gray-300 text-sm
                      focus:border-emerald-600 focus:ring-emerald-600
                      dark:border-gray-600 dark:bg-gray-800">
    </div>

    {{-- Botones --}}
    <div class="flex gap-2">
        <button type="submit"
                class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white
                       hover:bg-emerald-600">
            Filtrar
        </button>
        @if (request()->hasAny(['ubicacion_id', 'desde', 'hasta']))
            <a href="{{ route('entradas.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium
                      hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800">
                Limpiar
            </a>
        @endif
    </div>

    {{-- Nueva entrada (empujado a la derecha) --}}
    <div class="ml-auto">
        <a href="{{ route('entradas.create') }}"
           class="rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-gray-900
                  hover:bg-amber-500 dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500">
            + Nueva entrada
        </a>
    </div>
</form>



    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm
                dark:border-gray-700 dark:bg-gray-900">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide
                           text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    <th class="px-4 py-3">Folio</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Bodega</th>
                    <th class="px-4 py-3">Origen</th>
                    <th class="px-4 py-3">Variedades</th>
                    <th class="px-4 py-3 text-right">Toneladas</th>
                    <th class="px-4 py-3 text-right">Bultos</th>
                    <th class="px-4 py-3">Capturó</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($entradas as $entrada)
                    <tr class="border-b border-gray-100 hover:bg-gray-50
                               dark:border-gray-800 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 font-mono font-semibold">{{ $entrada->folio }}</td>
                        <td class="px-4 py-3">{{ $entrada->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $entrada->ubicacion->nombre }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{ $entrada->origen ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $entrada->detalles->pluck('variedad.nombre')->join(', ') }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($entrada->detalles->sum('toneladas'), 3) }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ number_format($entrada->detalles->sum('bultos')) }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{ $entrada->usuario->name }}
                        </td>

                        <td class="px-4 py-3 font-mono font-semibold">
                            <a href="{{ route('entradas.show', $entrada) }}"
                            class="text-emerald-700 hover:underline dark:text-emerald-400">
                                {{ $entrada->folio }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400">
    @if (request()->hasAny(['ubicacion_id', 'desde', 'hasta']))
        No hay entradas que coincidan con los filtros.
    @else
        Aún no hay entradas registradas.
        <a href="{{ route('entradas.create') }}"
           class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">
            Registra la primera
        </a>
    @endif
</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $entradas->links() }}
    </div>

</x-layouts.app>