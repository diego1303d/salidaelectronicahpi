<x-layouts.app>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Salidas</h1>
            <a href="{{ route('salidas.create') }}" class="rounded-lg bg-green-700 text-white px-4 py-2 font-medium">
                + Nueva salida
            </a>
        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('salidas.index') }}"
              class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6 items-end">
            <div>
                <label class="block text-sm mb-1">Folio</label>
                <input type="text" name="folio" value="{{ request('folio') }}"
                       placeholder="S-2026-…" class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Estatus</label>
                <select name="estatus" class="w-full rounded-lg border px-3 py-2">
                    <option value="">Todos</option>
                    @foreach (\App\Enums\SalidaEstatus::cases() as $e)
                        <option value="{{ $e->value }}" @selected(request('estatus') === $e->value)>
                            {{ ucfirst($e->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Tipo</label>
                <select name="tipo" class="w-full rounded-lg border px-3 py-2">
                    <option value="">Todos</option>
                    @foreach (\App\Enums\SalidaTipo::cases() as $t)
                        <option value="{{ $t->value }}" @selected(request('tipo') === $t->value)>
                            {{ ucfirst($t->value) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Bodega origen</label>
                <select name="bodega" class="w-full rounded-lg border px-3 py-2">
                    <option value="">Todas</option>
                    @foreach ($ubicaciones as $u)
                        <option value="{{ $u->id }}" @selected(request('bodega') == $u->id)>
                            {{ $u->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm mb-1">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}"
                       class="w-full rounded-lg border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}"
                       class="w-full rounded-lg border px-3 py-2">
            </div>

            <div class="col-span-2 md:col-span-6 flex gap-3">
                <button type="submit" class="rounded-lg bg-green-700 text-white px-4 py-2 font-medium">
                    Filtrar
                </button>
                <a href="{{ route('salidas.index') }}" class="rounded-lg border px-4 py-2">
                    Limpiar
                </a>
            </div>
        </form>

        {{-- Tabla --}}
        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b bg-green-900/5">
                        <th class="px-3 py-2">Folio</th>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2">Origen</th>
                        <th class="px-3 py-2">Destino / Cliente</th>
                        <th class="px-3 py-2 text-right">Toneladas</th>
                        <th class="px-3 py-2 text-right">Bultos</th>
                        <th class="px-3 py-2">Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salidas as $salida)
                        <tr class="border-b hover:bg-green-900/5">
                            <td class="px-3 py-2 font-mono">
                                <a href="{{ route('salidas.show', $salida) }}"
                                   class="text-green-800 font-bold hover:underline">
                                    {{ $salida->folio }}
                                </a>
                            </td>
                            <td class="px-3 py-2">{{ $salida->fecha->format('d/m/Y') }}</td>
                            <td class="px-3 py-2">{{ ucfirst($salida->tipo->value) }}</td>
                            <td class="px-3 py-2">{{ $salida->origen->nombre }}</td>
                            <td class="px-3 py-2">
                                {{ $salida->tipo === \App\Enums\SalidaTipo::Traspaso
                                    ? $salida->destino->nombre
                                    : $salida->cliente_nombre }}
                            </td>
                            <td class="px-3 py-2 text-right">{{ $salida->total_toneladas }}</td>
                            <td class="px-3 py-2 text-right">{{ $salida->total_bultos }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-block rounded-full border px-3 py-1 text-xs font-bold uppercase {{ $salida->estatus->color() }}">
                                    {{ $salida->estatus->value }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-8 text-center opacity-60">
                                No hay salidas con esos filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $salidas->links() }}
        </div>
    </div>
</x-layouts.app>