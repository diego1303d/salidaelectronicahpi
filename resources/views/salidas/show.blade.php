<x-layouts.app>
    <div class="max-w-3xl mx-auto">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Encabezado: folio protagonista + estatus --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm opacity-60">{{ ucfirst($salida->tipo->value) }}</p>
                <h1 class="text-3xl font-bold font-mono">{{ $salida->folio }}</h1>
            </div>
            <span class="inline-block rounded-full border px-4 py-2 text-sm font-bold uppercase {{ $salida->estatus->color() }}">
                {{ $salida->estatus->value }}
            </span>
        </div>

        {{-- Datos generales --}}
        <div class="rounded-lg border p-4 mb-6 grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <p><strong>Fecha:</strong> {{ $salida->fecha->format('d/m/Y') }}</p>
            <p><strong>Bodega origen:</strong> {{ $salida->origen->nombre }}</p>

            @if ($salida->tipo === \App\Enums\SalidaTipo::Traspaso)
                <p><strong>Bodega destino:</strong> {{ $salida->destino->nombre }}</p>
            @else
                <p><strong>Cliente:</strong> {{ $salida->cliente_nombre }}</p>
                <p><strong>Teléfono:</strong> {{ $salida->cliente_telefono }}</p>
                <p><strong>Forma de pago:</strong> {{ ucfirst($salida->forma_pago->value) }}</p>
            @endif

            <p><strong>Capturó:</strong> {{ $salida->usuario->name }}</p>

            @if ($salida->fecha_entrega)
                <p><strong>Entregada el:</strong> {{ $salida->fecha_entrega->format('d/m/Y H:i') }}</p>
            @endif

            @if ($salida->observaciones)
                <p class="col-span-2"><strong>Observaciones:</strong> {{ $salida->observaciones }}</p>
            @endif
        </div>

        {{-- Partidas --}}
        <div class="rounded-lg border p-4 mb-6">
            <h2 class="font-bold mb-3">Partidas</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-1">Variedad</th>
                        <th class="py-1 text-right">Toneladas</th>
                        <th class="py-1 text-right">Bultos</th>
                        @if ($salida->tipo === \App\Enums\SalidaTipo::Venta)
                            <th class="py-1 text-right">Precio/ton</th>
                            <th class="py-1 text-right">Importe</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salida->detalles as $d)
                        <tr class="border-b">
                            <td class="py-1">{{ $d->variedad->nombre }}</td>
                            <td class="py-1 text-right">{{ $d->toneladas }}</td>
                            <td class="py-1 text-right">{{ $d->bultos }}</td>
                            @if ($salida->tipo === \App\Enums\SalidaTipo::Venta)
                                <td class="py-1 text-right">${{ number_format($d->precio_tonelada, 2) }}</td>
                                <td class="py-1 text-right">${{ number_format($d->importe, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td class="py-1">Total</td>
                        <td class="py-1 text-right">{{ $salida->total_toneladas }}</td>
                        <td class="py-1 text-right">{{ $salida->total_bultos }}</td>
                        @if ($salida->tipo === \App\Enums\SalidaTipo::Venta)
                            <td></td>
                            <td class="py-1 text-right">${{ number_format($salida->total_importe, 2) }}</td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Historial --}}
        <div class="rounded-lg border p-4 mb-6">
            <h2 class="font-bold mb-3">Historial</h2>
            <ul class="text-sm space-y-2">
                @foreach ($salida->historial as $h)
                    <li class="flex justify-between border-b pb-1">
                        <span>
                            {{ $h->estatus_anterior ? ucfirst($h->estatus_anterior->value) . ' → ' : '' }}
                            <strong>{{ ucfirst($h->estatus_nuevo->value) }}</strong>
                            — {{ $h->observaciones }}
                        </span>
                        <span class="opacity-60">
                            {{ $h->usuario->name }} · {{ $h->created_at->format('d/m/Y H:i') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <a href="{{ route('salidas.index') }}" class="rounded-lg border px-4 py-2 inline-block">
            ← Volver al listado
        </a>
    </div>
</x-layouts.app>