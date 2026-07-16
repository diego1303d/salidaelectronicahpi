<x-layouts.app :title="'Entrada ' . $entrada->folio">

    <x-page-header
        :title="'Entrada ' . $entrada->folio"
        subtitle="Detalle del ingreso de inventario" />

    {{-- Datos generales --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                dark:border-gray-700 dark:bg-gray-900">
        <dl class="grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Folio</dt>
                <dd class="mt-1 font-mono font-semibold">{{ $entrada->folio }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Fecha</dt>
                <dd class="mt-1">{{ $entrada->fecha->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Bodega</dt>
                <dd class="mt-1">{{ $entrada->ubicacion->nombre }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Capturó</dt>
                <dd class="mt-1">{{ $entrada->usuario->name }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Origen</dt>
                <dd class="mt-1">{{ $entrada->origen ?? '—' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Observaciones</dt>
                <dd class="mt-1">{{ $entrada->observaciones ?? '—' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Registrada en el sistema</dt>
                <dd class="mt-1 text-gray-500 dark:text-gray-400">
                    {{ $entrada->created_at->format('d/m/Y H:i') }}
                </dd>
            </div>
        </dl>
    </div>

    {{-- Partidas --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                dark:border-gray-700 dark:bg-gray-900">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
            Partidas
        </h2>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide
                           text-gray-500 dark:border-gray-700 dark:text-gray-400">
                    <th class="pb-2 pr-3">Variedad</th>
                    <th class="pb-2 pr-3 text-right">Kilos</th>
                    <th class="pb-2 text-right">Bultos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entrada->detalles as $detalle)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-2 pr-3">{{ $detalle->variedad->nombre }}</td>
                        <td class="py-2 pr-3 text-right font-mono">
                            {{ number_format($detalle->toneladas, 3) }}
                        </td>
                        <td class="py-2 text-right font-mono">
                            {{ number_format($detalle->bultos) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-semibold">
                    <td class="pt-3 pr-3 text-right">Totales:</td>
                    <td class="pt-3 pr-3 text-right font-mono">
                        {{ number_format($entrada->detalles->sum('toneladas'), 3) }}
                    </td>
                    <td class="pt-3 text-right font-mono">
                        {{ number_format($entrada->detalles->sum('bultos')) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Regresar --}}
    <div class="mt-6">
        <a href="{{ route('entradas.index') }}"
           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium
                  hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800">
            ← Volver al listado
        </a>
    </div>

</x-layouts.app>
