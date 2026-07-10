<x-layouts.app>

    <div class="mx-auto w-full max-w-2xl space-y-6"
         x-data="{
            clave: {{ Js::from(old('clave', '')) }},
            nombre: {{ Js::from(old('nombre', '')) }},
            direccion: {{ Js::from(old('direccion', '')) }},
            activo: {{ old('activo', 1) ? 'true' : 'false' }}
         }">

        {{-- ══════════════ Encabezado con ícono ══════════════ --}}
        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl
                        bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-500/25
                        dark:from-emerald-600 dark:to-emerald-800 dark:shadow-emerald-900/40">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">
                    Catálogos · Ubicaciones
                </p>
                <h1 class="mt-0.5 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Nueva ubicación
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    La clave identifica a la bodega en folios y reportes.
                </p>
            </div>
        </div>

        {{-- ══════════════ Errores de validación ══════════════ --}}
        @if ($errors->any())
            <div class="flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800
                        dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                <div>
                    <p class="font-semibold">Revisa lo siguiente:</p>
                    <ul class="mt-1 list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ══════════════ Formulario ══════════════ --}}
        <form method="POST" action="{{ route('ubicaciones.store') }}"
              class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm
                     dark:border-gray-700 dark:bg-gray-800">
            @csrf

            {{-- Franja decorativa verde → dorado --}}
            <div class="h-1.5 bg-gradient-to-r from-emerald-600 via-emerald-500 to-amber-400"></div>

            <div class="space-y-6 p-6 sm:p-8">

                {{-- Clave + Nombre --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                    {{-- Clave --}}
                    <div>
                        <label for="clave" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                            Clave <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">

                            <input type="text"
                                   id="clave"
                                   name="clave"
                                   x-model="clave"
                                   x-on:input="clave = clave.toUpperCase()"
                                   required
                                   maxlength="10"
                                   autofocus
                                   placeholder="BOD"
                                   class="w-full rounded-lg border py-2.5 pl-9 pr-3 text-sm font-semibold uppercase tracking-wide text-gray-900 placeholder-gray-400 transition
                                          focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                                          dark:bg-gray-900 dark:text-white dark:placeholder-gray-600
                                          {{ $errors->has('clave') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            <span x-text="clave.length"></span>/10 caracteres
                        </p>
                        @error('clave')
                            <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre --}}
                    <div class="sm:col-span-2">
                        <label for="nombre" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                            Nombre de la ubicación <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">

                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   x-model="nombre"
                                   required
                                   maxlength="100"
                                   placeholder="Ej. Bodega Villagrán"
                                   class="w-full rounded-lg border py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 transition
                                          focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                                          dark:bg-gray-900 dark:text-white dark:placeholder-gray-600
                                          {{ $errors->has('nombre') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                        </div>
                        @error('nombre')
                            <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Dirección --}}
                <div>
                    <label for="direccion" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                        Dirección
                        <span class="ml-1 text-xs font-normal text-gray-400 dark:text-gray-500">(opcional)</span>
                    </label>
                    <div class="relative">

                        <textarea id="direccion"
                                  name="direccion"
                                  x-model="direccion"
                                  rows="2"
                                  maxlength="255"
                                  placeholder="Calle, número, colonia, municipio…"
                                  class="w-full resize-none rounded-lg border py-2.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 transition
                                         focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                                         dark:bg-gray-900 dark:text-white dark:placeholder-gray-600
                                         {{ $errors->has('direccion') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}"></textarea>
                    </div>
                    @error('direccion')
                        <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estatus (activo = 1, inactivo = 0) — el panel cambia de color con el toggle --}}
                <label class="flex cursor-pointer items-center justify-between gap-4 rounded-xl border px-4 py-3.5 transition"
                       :class="activo
                                ? 'border-emerald-200 bg-emerald-50/60 dark:border-emerald-800 dark:bg-emerald-900/20'
                                : 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40'">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition"
                              :class="activo
                                        ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400'
                                        : 'bg-gray-200 text-gray-400 dark:bg-gray-700 dark:text-gray-500'">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"
                               x-text="activo ? 'Ubicación activa' : 'Ubicación inactiva'"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Solo las activas aparecen al capturar entradas y salidas.
                            </p>
                        </div>
                    </div>

                    {{-- El hidden manda 0 si el checkbox va desmarcado; marcado manda 1 --}}
                    <span class="relative inline-flex shrink-0 items-center">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox"
                               name="activo"
                               value="1"
                               x-model="activo"
                               class="peer sr-only">
                        <span class="peer h-6 w-11 rounded-full bg-gray-300 transition
                                     after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all
                                     peer-checked:bg-emerald-600 peer-checked:after:translate-x-5
                                     peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500/50
                                     dark:bg-gray-600 dark:peer-checked:bg-emerald-500"></span>
                    </span>
                </label>
            </div>

            {{-- ══════════════ Acciones ══════════════ --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4
                        dark:border-gray-700 dark:bg-gray-900/40 sm:px-8">
                <a href="{{ route('ubicaciones.index') }}"
                   class="rounded-lg px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-100
                          dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-amber-400 px-5 py-2.5 text-sm font-bold text-gray-900 shadow-md shadow-amber-400/30
                               transition hover:-translate-y-0.5 hover:bg-amber-500 hover:shadow-lg hover:shadow-amber-400/40
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2
                               dark:bg-emerald-600 dark:text-white dark:shadow-emerald-900/40 dark:hover:bg-emerald-500
                               dark:focus-visible:ring-emerald-500 dark:focus-visible:ring-offset-gray-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Guardar ubicación
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
