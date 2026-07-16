<x-layouts.app title="Nueva entrada">

    <x-page-header
        title="Nueva entrada"
        subtitle="Registra el ingreso de trigo a una bodega" />

    {{-- Resumen de errores --}}
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

    <form method="POST" action="{{ route('entradas.store') }}" x-data="entradaForm()">
        @csrf

        {{-- =============== DATOS GENERALES =============== --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-900">
            <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Datos generales
            </h2>

            <div class="grid gap-4 md:grid-cols-2">
                {{-- Bodega --}}
                <div>
                    <label for="ubicacion_id" class="mb-1 block text-sm font-medium">Bodega *</label>
                    <div class="relative">
                        <select id="ubicacion_id" name="ubicacion_id" required
                                class="w-full appearance-none rounded-lg border-gray-300 pr-10
                                       focus:border-emerald-600 focus:ring-emerald-600
                                       dark:border-gray-600 dark:bg-gray-800">
                            <option value="">— Selecciona bodega —</option>
                            @foreach ($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->id }}" @selected(old('ubicacion_id') == $ubicacion->id)>
                                    {{ $ubicacion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    @error('ubicacion_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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

                {{-- Origen --}}
                <div>
                    <label for="origen" class="mb-1 block text-sm font-medium">Origen</label>
                    <input type="text" id="origen" name="origen" maxlength="150"
                           value="{{ old('origen') }}"
                           placeholder="Proveedor, campo, cosecha…"
                           class="w-full rounded-lg border-gray-300
                                  focus:border-emerald-600 focus:ring-emerald-600
                                  dark:border-gray-600 dark:bg-gray-800">
                    @error('origen') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Observaciones --}}
                <div>
                    <label for="observaciones" class="mb-1 block text-sm font-medium">Observaciones</label>
                    <input type="text" id="observaciones" name="observaciones" maxlength="255"
                           value="{{ old('observaciones') }}"
                           class="w-full rounded-lg border-gray-300
                                  focus:border-emerald-600 focus:ring-emerald-600
                                  dark:border-gray-600 dark:bg-gray-800">
                    @error('observaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- =============== VARIEDADES (renglones dinámicos) =============== --}}
        <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                    dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Variedades
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
                            <th class="pb-2 pr-3 w-40">KG</th>
                            <th class="pb-2 pr-3 w-32">Bultos</th>
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
                            <td class="pt-3 pr-3 text-right" x-text="totalToneladas"></td>
                            <td class="pt-3 pr-3 text-right" x-text="totalBultos"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- =============== ACCIONES =============== --}}
        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('entradas.index') }}"
               class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium
                      hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-lg bg-amber-400 px-5 py-2 text-sm font-semibold text-gray-900
                           hover:bg-amber-500 dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500">
                Registrar entrada
            </button>
        </div>
    </form>

    <script>
        function entradaForm() {
            return {
                renglones: @js(old('detalles', [['variedad_id' => '', 'toneladas' => '', 'bultos' => '']])),

                agregar() {
                    this.renglones.push({ variedad_id: '', toneladas: '', bultos: '' });
                },

                quitar(i) {
                    if (this.renglones.length > 1) this.renglones.splice(i, 1);
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
            }
        }
    </script>

</x-layouts.app>
