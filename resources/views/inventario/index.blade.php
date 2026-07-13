<x-layouts.app title="Inventario">

    <x-page-header
        title="Inventario actual"
        subtitle="Existencias por bodega y variedad" />

    @if ($variedades->isEmpty())
        <div class="rounded-xl border border-gray-200 bg-white p-10 text-center text-gray-500
                    shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
            Aún no hay inventario.
            <a href="{{ route('entradas.create') }}"
               class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">
                Registra la primera entrada
            </a>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm
                    dark:border-gray-700 dark:bg-gray-900">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase tracking-wide
                               text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <th class="px-4 py-3 text-left">Variedad</th>
                        @foreach ($ubicaciones as $ubicacion)
                            <th class="px-4 py-3 text-right">{{ $ubicacion->nombre }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-right bg-gray-50 dark:bg-gray-800/50">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($variedades as $variedad)
                        <tr class="border-b border-gray-100 hover:bg-gray-50
                                   dark:border-gray-800 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 font-medium">
                                {{ $variedad->nombre }}
                                @unless ($variedad->activo)
                                    <span class="ml-1 rounded bg-gray-200 px-1.5 py-0.5 text-xs text-gray-600
                                                 dark:bg-gray-700 dark:text-gray-300">inactiva</span>
                                @endunless
                            </td>

                            @foreach ($ubicaciones as $ubicacion)
                                @php
                                    $celda = $matriz->get($ubicacion->id . '-' . $variedad->id);
                                @endphp
                                <td class="px-4 py-3 text-right">
                                    @if ($celda && ($celda->toneladas > 0 || $celda->bultos > 0))
                                        <span class="block font-mono font-semibold">
                                            {{ number_format($celda->toneladas, 3) }} t
                                        </span>
                                        <span class="block font-mono text-xs text-gray-500 dark:text-gray-400">
                                            {{ number_format($celda->bultos) }} bultos
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600">—</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Total por variedad --}}
                            <td class="px-4 py-3 text-right bg-gray-50 dark:bg-gray-800/50">
                                <span class="block font-mono font-semibold">
                                    {{ number_format($inventarios->where('variedad_id', $variedad->id)->sum('toneladas'), 3) }} t
                                </span>
                                <span class="block font-mono text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format($inventarios->where('variedad_id', $variedad->id)->sum('bultos')) }} bultos
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold bg-gray-50 dark:bg-gray-800/50">
                        <td class="px-4 py-3 text-right">Total por bodega:</td>
                        @foreach ($ubicaciones as $ubicacion)
                            <td class="px-4 py-3 text-right">
                                <span class="block font-mono">
                                    {{ number_format($inventarios->where('ubicacion_id', $ubicacion->id)->sum('toneladas'), 3) }} t
                                </span>
                                <span class="block font-mono text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format($inventarios->where('ubicacion_id', $ubicacion->id)->sum('bultos')) }} bultos
                                </span>
                            </td>
                        @endforeach
                        {{-- Gran total --}}
                        <td class="px-4 py-3 text-right bg-amber-50 dark:bg-emerald-950/40">
                            <span class="block font-mono">
                                {{ number_format($inventarios->sum('toneladas'), 3) }} t
                            </span>
                            <span class="block font-mono text-xs text-gray-500 dark:text-gray-400">
                                {{ number_format($inventarios->sum('bultos')) }} bultos
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

</x-layouts.app>