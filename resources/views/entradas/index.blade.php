<x-layouts.app >

   {{-- ══════════════ Mensaje flash (se oculta solo a los 4 s) ══════════════ --}}
    @if (session('success'))
       <div x-data="{ visible: true }"
                 x-init="setTimeout(() => visible = false, 4000)"
                 x-show="visible"
                 x-transition.opacity.duration.400ms
                 class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800
                        dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ session('success') }}
            </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700
                    dark:border-red-800 dark:bg-red-950/50 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif


 {{-- ══════════════ Encabezado de la página ══════════════ --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">
                   Movimientos
                </p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                   Entradas de inventario
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                   Ingresos de trigo a las bodegas
                </p>
            </div>

            {{-- Botón dorado: mismo acento que "Nueva Salida", contrasta en tema claro y oscuro --}}
            {{-- TODO: cambia href="" por la ruta variedades.create cuando la tengas --}}

        </div>



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
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm
                    dark:border-gray-700 dark:bg-gray-800">

    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/95">
                <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide
                           text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    <th class="px-4 py-3">Folio</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Bodega</th>
                    <th class="px-4 py-3">Origen</th>
                    <th class="px-4 py-3">Variedades</th>
                    <th class="px-4 py-3 ">Kilos</th>
                    <th class="px-4 py-3 ">Bultos</th>
                    <th class="px-4 py-3">Capturó</th>
                    <th class="px-4 py-3">Vista</th>
                </tr>
            </thead>
            <tbody x-ref="tbody" class="divide-y divide-gray-100 dark:divide-gray-700/60">
                @forelse ($entradas as $entrada)
                    <tr class="transition-colors hover:bg-emerald-50/60 dark:hover:bg-emerald-900/10">
                        <td class="px-4 py-3">
                                 <span class="font-semibold text-gray-900 dark:text-white">
                                     {{ $entrada->folio }}
                                 </span>
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $entrada->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
<span class="font-semibold text-gray-900 dark:text-white">
                            {{ $entrada->ubicacion->nombre }}
</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{ $entrada->origen ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $entrada->detalles->pluck('variedad.nombre')->join(', ') }}
                        </td>
                        <td class="px-4 py-3  font-mono">
                            {{ number_format($entrada->detalles->sum('toneladas'), 3) }}
                        </td>
                        <td class="px-4 py-3  font-mono">
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
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $entradas->links() }}
    </div>

</x-layouts.app>
