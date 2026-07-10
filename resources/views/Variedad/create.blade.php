<x-layouts.app>

    <div class="mx-auto w-full max-w-2xl space-y-6"
         x-data="{
            nombre: {{ Js::from(old('nombre', '')) }},
            categoria: {{ Js::from(old('categoria', '')) }},
            peso: {{ Js::from((float) old('peso_bulto_kg', 25)) }},
            activa: {{ old('activa', 1) ? 'true' : 'false' }}
         }">

        {{-- ══════════════ Encabezado ══════════════ --}}
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">
                Catálogos · Variedades
            </p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Nueva variedad
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Registra la variedad con su categoría y peso por bulto. Ese peso valida bultos vs toneladas en entradas y salidas.
            </p>
        </div>

        {{-- ══════════════ Errores de validación ══════════════ --}}
        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800
                        dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
                <p class="font-semibold">Revisa lo siguiente:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ══════════════ Formulario ══════════════ --}}
        <form method="POST" action="{{ route('Variedad.store') }}"
              class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                     dark:border-gray-700 dark:bg-gray-800">
            @csrf

            {{-- Nombre --}}
            <div>
                <label for="nombre" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                    Nombre de la variedad <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       x-model="nombre"
                       required
                       maxlength="100"
                       autofocus
                       placeholder="Ej. Trigo Cristalino"
                       class="w-full rounded-lg border px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition
                              focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                              dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                              {{ $errors->has('nombre') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                @error('nombre')
                    <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoría + Peso lado a lado en pantallas medianas --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Categoría (combo alimentado por el enum VariedadCategoria) --}}
                <div>
                    <label for="categoria" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                        Categoría <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="categoria"
                                name="categoria"
                                x-model="categoria"
                                required
                                class="w-full appearance-none rounded-lg border px-3 py-2.5 pr-9 text-sm text-gray-900 transition
                                       focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                                       dark:bg-gray-900 dark:text-white
                                       {{ $errors->has('categoria') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                            <option value="" disabled>Selecciona una categoría</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                            @endforeach
                        </select>
                        {{-- Flecha del combo (appearance-none quita la nativa para que se vea igual en ambos temas) --}}
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                    @error('categoria')
                        <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Peso por bulto --}}
                <div>
                    <label for="peso_bulto_kg" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                        Peso por bulto <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number"
                               id="peso_bulto_kg"
                               name="peso_bulto_kg"
                               x-model.number="peso"
                               required
                               step="0.01"
                               min="0.01"
                               max="200"
                               class="w-full rounded-lg border py-2.5 pl-3 pr-10 text-sm text-gray-900 tabular-nums transition
                                      focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                                      dark:bg-gray-900 dark:text-white
                                      {{ $errors->has('peso_bulto_kg') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-gray-400">
                            kg
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="peso > 0" x-cloak>
                        ≈ <span class="font-bold text-emerald-700 dark:text-emerald-400"
                                x-text="Math.round(1000 / peso)"></span> bultos por tonelada
                    </p>
                    @error('peso_bulto_kg')
                        <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Estatus (activa = 1, inactiva = 0) --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Variedad activa</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Solo las variedades activas aparecen al capturar entradas y salidas.
                    </p>
                </div>

                {{-- El hidden manda 0 si el checkbox va desmarcado; marcado manda 1 --}}
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="hidden" name="activa" value="0">
                    <input type="checkbox"
                           name="activa"
                           value="1"
                           x-model="activa"
                           class="peer sr-only">
                    <div class="peer h-6 w-11 rounded-full bg-gray-300 transition
                                after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all
                                peer-checked:bg-emerald-600 peer-checked:after:translate-x-5
                                peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500/50
                                dark:bg-gray-600 dark:peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            {{-- ══════════════ Vista previa: así quedará en el catálogo ══════════════ --}}
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                    Así se verá en el catálogo
                </p>
                <div class="flex items-center gap-3 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-3
                            dark:border-gray-600 dark:bg-gray-900/50">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700
                                 dark:bg-emerald-900/40 dark:text-emerald-400"
                          x-text="(nombre.trim()[0] || '?').toUpperCase()"></span>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-white"
                           x-text="nombre.trim() || 'Nombre de la variedad'"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <span x-text="categoria || 'Sin categoría'"></span>
                            <template x-if="peso > 0">
                                <span> · <span x-text="peso.toFixed(2)"></span> kg por bulto</span>
                            </template>
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"
                          :class="activa
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
                                    : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                        <span class="inline-flex h-2 w-2 rounded-full"
                              :class="activa ? 'bg-emerald-500' : 'bg-gray-400'"></span>
                        <span x-text="activa ? 'Activa' : 'Inactiva'"></span>
                    </span>
                </div>
            </div>

            {{-- ══════════════ Acciones ══════════════ --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
                <a href="{{ route('Variedad.index') }}"
                   class="rounded-lg px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-100
                          dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-400 px-5 py-2.5 text-sm font-bold text-gray-900 shadow-sm
                               transition hover:bg-amber-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2
                               dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500 dark:focus-visible:ring-emerald-500 dark:focus-visible:ring-offset-gray-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Guardar variedad
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>