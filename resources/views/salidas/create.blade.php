<x-layouts.app title="Nueva salida">

    @if (session('success'))
    <div class="mb-6 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-sm text-emerald-800
                dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
        {{ session('success') }}
    </div>
@endif

    <x-page-header
        title="Nueva salida"
        subtitle="Registra una venta o un traspaso entre bodegas" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700
                    dark:border-red-800 dark:bg-red-950/50 dark:text-red-300">
            <p class="mb-1 font-semibold">Revisa lo siguiente:</p>
            <ul class="list-disc space-y-0.5 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700
                    dark:border-red-800 dark:bg-red-950/50 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('salidas.store') }}" x-data="salidaForm()">
        @csrf

        {{-- =============== TIPO DE SALIDA =============== --}}
        <div class="mb-6 grid grid-cols-2 gap-3">
            <label class="cursor-pointer rounded-xl border-2 p-4 text-center transition"
                   :class="esVenta
                       ? 'border-emerald-600 bg-emerald-50 dark:bg-emerald-950/40'
                       : 'border-gray-200 dark:border-gray-700'">
                <input type="radio" name="tipo" value="venta" x-model="tipo" class="sr-only">
                <span class="block text-lg font-bold">Venta</span>
                <span class="block text-xs text-gray-500 dark:text-gray-400">A un cliente externo</span>
            </label>

            <label class="cursor-pointer rounded-xl border-2 p-4 text-center transition"
                   :class="!esVenta
                       ? 'border-emerald-600 bg-emerald-50 dark:bg-emerald-950/40'
                       : 'border-gray-200 dark:border-gray-700'">
                <input type="radio" name="tipo" value="traspaso" x-model="tipo" class="sr-only">
                <span class="block text-lg font-bold">Traspaso</span>
                <span class="block text-xs text-gray-500 dark:text-gray-400">Entre bodegas propias</span>
            </label>
        </div>

        {{-- =============== DATOS GENERALES =============== --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-900">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Datos generales
            </h2>

            <div class="grid gap-4 md:grid-cols-2">
                {{-- Bodega origen --}}
                <div>
                    <label for="ubicacion_origen_id" class="mb-1 block text-sm font-medium">Bodega origen *</label>
                    <select id="ubicacion_origen_id" name="ubicacion_origen_id" x-model="origenId" required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-emerald-600 focus:ring-emerald-600
                                   dark:border-gray-600 dark:bg-gray-800">
                        <option value="">— Selecciona bodega —</option>
                        @foreach ($ubicaciones as $ubicacion)
                            <option value="{{ $ubicacion->id }}">{{ $ubicacion->nombre }}</option>
                        @endforeach
                    </select>
                    @error('ubicacion_origen_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label for="fecha" class="mb-1 block text-sm font-medium">Fecha *</label>
                    <input type="date" id="fecha" name="fecha" required
                           value="{{ old('fecha', now()->toDateString()) }}"
                           max="{{ now()->toDateString() }}"
                           class="w-full rounded-lg border-gray-300
                                  focus:border-emerald-600 focus:ring-emerald-600
                                  dark:border-gray-600 dark:bg-gray-800">
                    @error('fecha') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- ===== SOLO VENTA ===== --}}
                <template x-if="esVenta">
                    <div class="contents">
                        <div>
                            <label for="cliente_nombre" class="mb-1 block text-sm font-medium">Cliente *</label>
                            <input type="text" id="cliente_nombre" name="cliente_nombre" maxlength="150"
                                   x-model="clienteNombre"
                                   class="w-full rounded-lg border-gray-300
                                          focus:border-emerald-600 focus:ring-emerald-600
                                          dark:border-gray-600 dark:bg-gray-800">
                            @error('cliente_nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="cliente_telefono" class="mb-1 block text-sm font-medium">Teléfono</label>
                            <input type="text" id="cliente_telefono" name="cliente_telefono" maxlength="20"
                                   x-model="clienteTelefono"
                                   class="w-full rounded-lg border-gray-300
                                          focus:border-emerald-600 focus:ring-emerald-600
                                          dark:border-gray-600 dark:bg-gray-800">
                            @error('cliente_telefono') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="forma_pago" class="mb-1 block text-sm font-medium">Forma de pago *</label>
                            <select id="forma_pago" name="forma_pago" x-model="formaPago"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-emerald-600 focus:ring-emerald-600
                                           dark:border-gray-600 dark:bg-gray-800">
                                <option value="">— Selecciona —</option>
                                @foreach ($formasPago as $fp)
                                    <option value="{{ $fp->value }}">{{ $fp->etiqueta() }}</option>
                                @endforeach
                            </select>
                            @error('forma_pago') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </template>

                {{-- ===== SOLO TRASPASO ===== --}}
                <template x-if="!esVenta">
                    <div>
                        <label for="ubicacion_destino_id" class="mb-1 block text-sm font-medium">Bodega destino *</label>
                        <select id="ubicacion_destino_id" name="ubicacion_destino_id" x-model="destinoId"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-emerald-600 focus:ring-emerald-600
                                       dark:border-gray-600 dark:bg-gray-800">
                            <option value="">— Selecciona bodega —</option>
                            @foreach ($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->id }}"
                                        :disabled="origenId == '{{ $ubicacion->id }}'">
                                    {{ $ubicacion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            No puede ser la misma que la bodega origen.
                        </p>
                        @error('ubicacion_destino_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </template>

                {{-- Observaciones --}}
                <div class="md:col-span-2">
                    <label for="observaciones" class="mb-1 block text-sm font-medium">Observaciones</label>
                    <input type="text" id="observaciones" name="observaciones" maxlength="255"
                           value="{{ old('observaciones') }}"
                           class="w-full rounded-lg border-gray-300
                                  focus:border-emerald-600 focus:ring-emerald-600
                                  dark:border-gray-600 dark:bg-gray-800">
                </div>
            </div>
        </div>

        {{-- =============== PARTIDAS =============== --}}
        <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Partidas
                </h2>
                <button type="button" @click="agregar()"
                        class="rounded-lg bg-amber-400 px-3 py-1.5 text-sm font-semibold text-gray-900
                               hover:bg-amber-500 dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500">
                    + Agregar variedad
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide
                                   text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            <th class="pb-2 pr-3">Variedad</th>
                            <th class="pb-2 pr-3 w-36">Kilos</th>
                            <th class="pb-2 pr-3 w-28">Bultos</th>
                            <th class="pb-2 pr-3 w-36 text-right" x-show="esVenta">Precio</th>
                            <th class="pb-2 pr-3 w-36 text-right" x-show="esVenta">Importe</th>
                            <th class="pb-2 w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(renglon, i) in renglones" :key="i">
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2 pr-3">
                                    <select :name="`detalles[${i}][variedad_id]`" x-model="renglon.variedad_id" required
                                            class="w-full rounded-lg border-gray-300
                                                   focus:border-emerald-600 focus:ring-emerald-600
                                                   dark:border-gray-600 dark:bg-gray-800">
                                        <option value="">— Selecciona —</option>
                                        @foreach ($variedades as $variedad)
                                            <option value="{{ $variedad->id }}">{{ $variedad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 pr-3">
                                    <input type="number" step="0.001" min="0.001"
                                           :name="`detalles[${i}][toneladas]`" x-model="renglon.toneladas" required
                                           class="w-full rounded-lg border-gray-300 text-right
                                                  focus:border-emerald-600 focus:ring-emerald-600
                                                  dark:border-gray-600 dark:bg-gray-800">
                                </td>
                                <td class="py-2 pr-3">
                                    <input type="number" step="1" min="1"
                                           :name="`detalles[${i}][bultos]`" x-model="renglon.bultos" required
                                           class="w-full rounded-lg border-gray-300 text-right
                                                  focus:border-emerald-600 focus:ring-emerald-600
                                                  dark:border-gray-600 dark:bg-gray-800">
                                </td>
                                <td class="py-2 pr-3" x-show="esVenta">
                                    <input type="number" step="0.01" min="0.01"
                                           :name="`detalles[${i}][precio_tonelada]`"
                                           x-model="renglon.precio_tonelada"
                                           :disabled="!esVenta"
                                           :required="esVenta"
                                           class="w-full rounded-lg border-gray-300 text-right
                                                  focus:border-emerald-600 focus:ring-emerald-600
                                                  dark:border-gray-600 dark:bg-gray-800">
                                </td>
                                <td class="py-2 pr-3 text-right font-mono" x-show="esVenta"
                                    x-text="moneda(importeRenglon(renglon))">
                                </td>
                                <td class="py-2 text-center">
                                    <button type="button" @click="quitar(i)"
                                            x-show="renglones.length > 1"
                                            class="text-red-500 hover:text-red-700" title="Quitar renglón">
                                        ✕
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold">
                            <td class="pt-3 pr-3 text-right">Totales:</td>
                            <td class="pt-3 pr-3 text-right font-mono" x-text="totalToneladas"></td>
                            <td class="pt-3 pr-3 text-right font-mono" x-text="totalBultos"></td>
                            <td class="pt-3 pr-3" x-show="esVenta"></td>
                            <td class="pt-3 pr-3 text-right font-mono" x-show="esVenta"
                                x-text="moneda(totalImporte)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- =============== ACCIONES =============== --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ url()->previous() }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium
                      hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-lg bg-amber-400 px-5 py-2 text-sm font-semibold text-gray-900
                           hover:bg-amber-500 dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500">
                Registrar salida
            </button>
        </div>
    </form>

    <script>
        function salidaForm() {
            return {
                tipo:            @js(old('tipo', 'venta')),
                origenId:        @js(old('ubicacion_origen_id', '')),
                destinoId:       @js(old('ubicacion_destino_id', '')),
                clienteNombre:   @js(old('cliente_nombre', '')),
                clienteTelefono: @js(old('cliente_telefono', '')),
                formaPago:       @js(old('forma_pago', '')),
                renglones:       @js(old('detalles', [
                    ['variedad_id' => '', 'toneladas' => '', 'bultos' => '', 'precio_tonelada' => ''],
                ])),

                get esVenta() {
                    return this.tipo === 'venta';
                },

                agregar() {
                    this.renglones.push({ variedad_id: '', toneladas: '', bultos: '', precio_tonelada: '' });
                },

                quitar(i) {
                    if (this.renglones.length > 1) this.renglones.splice(i, 1);
                },

                importeRenglon(r) {
                    return (parseFloat(r.toneladas) || 0) * (parseFloat(r.precio_tonelada) || 0);
                },

                get totalToneladas() {
                    return this.renglones
                        .reduce((suma, r) => suma + (parseFloat(r.toneladas) || 0), 0)
                        .toFixed(3);
                },

                get totalBultos() {
                    return this.renglones
                        .reduce((suma, r) => suma + (parseInt(r.bultos) || 0), 0);
                },

                get totalImporte() {
                    return this.renglones
                        .reduce((suma, r) => suma + this.importeRenglon(r), 0);
                },

                moneda(valor) {
                    return valor.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
                },
            }
        }
    </script>

</x-layouts.app>
