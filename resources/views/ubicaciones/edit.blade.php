<x-layouts.app>

    <div class="mx-auto w-full max-w-2xl space-y-6"
         x-data="{
            clave: {{ Js::from(old('clave', $ubicacion->clave)) }},
            nombre: {{ Js::from(old('nombre', $ubicacion->nombre)) }},
            direccion: {{ Js::from(old('', $ubicacion->direccion)) }},
            activo: {{ old('activo', $ubicacion->activo) ? 'true' : 'false' }}
         }">

        {{-- ══════════════ Encabezado ══════════════ --}}
        <div>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Editar: {{ $ubicacion->nombre }}
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                   {{-- ══════════════ Poner instrucciones ══════════════ --}}
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
        <form method="POST" action="{{ route('ubicaciones.update', $ubicacion) }}"
              class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm
                     dark:border-gray-700 dark:bg-gray-800">
            @csrf
            {{-- Los forms HTML solo saben GET y POST; con esto Laravel
                 lo trata como PUT y lo enruta a update() --}}
            @method('PUT')

            {{-- Nombre --}}
            <div>
                <label for="clave" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                   Clave <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="clave"
                       name="clave"
                       x-model="clave"
                       required
                       maxlength="100"
                       autofocus
                       class="w-full rounded-lg border px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition
                              focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                              dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                              {{ $errors->has('clave') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                @error('clave')
                    <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoría + Peso lado a lado --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Categoría (combo alimentado por el enum VariedadCategoria) --}}
                <div>
                    <label for="categoria" class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                        Ubicacion <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="nombre"
                                name="nombre"
                                x-model="nombre"
                                required
                       class="w-full rounded-lg border px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition
                              focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                              dark:bg-gray-900 dark:text-white dark:placeholder-gray-500
                              {{ $errors->has('nombre') ? 'border-red-400 dark:border-red-600' : 'border-gray-300 dark:border-gray-600' }}">
                @error('nombre')
                    <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                </div>

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
            </div>

            {{-- Estatus (activo = 1, inactivo = 0) --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 dark:border-gray-700">
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Ubicacion activa</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Solo las Ubicaciones activas aparecen al capturar entradas y salidas.
                    </p>
                </div>

                {{-- El hidden manda 0 si el checkbox va desmarcado; marcado manda 1 --}}
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="hidden" name="activo" value="0">
                    <input type="checkbox"
                           name="activo"
                           value="1"
                           x-model="activo"
                           class="peer sr-only">
                    <div class="peer h-6 w-11 rounded-full bg-gray-300 transition
                                after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all
                                peer-checked:bg-emerald-600 peer-checked:after:translate-x-5
                                peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-500/50
                                dark:bg-gray-600 dark:peer-checked:bg-emerald-500"></div>
                </label>
            </div>



            {{-- ══════════════ Acciones ══════════════ --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
                <a href="{{ route('ubicaciones.index') }}"
                   class="rounded-lg px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-100
                          dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gold px-5 py-2.5 text-sm font-bold text-brand-dark shadow-sm
                               transition hover:bg-gold-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-2
                               dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500 dark:focus-visible:ring-emerald-500 dark:focus-visible:ring-offset-gray-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
