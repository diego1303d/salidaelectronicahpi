<x-layouts.app>



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

        {{-- ══════════════ Encabezado de la página ══════════════ --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">
                    Catálogos
                </p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Ubicaciones  de trigo
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">

                </p>
            </div>

            {{-- Botón dorado: mismo acento que "Nueva Salida", contrasta en tema claro y oscuro --}}
            {{-- TODO: cambia href="" por la ruta variedades.create cuando la tengas --}}
            <a  href="{{Route ('ubicaciones.create')}}"
               class="inline-flex items-center gap-2 self-start rounded-lg bg-gold px-4 py-2.5 text-sm font-bold text-brand-dark shadow-sm
       transition hover:bg-gold-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-2
       dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500 dark:focus-visible:ring-emerald-500 dark:focus-visible:ring-offset-gray-900">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nueva Ubicacion
            </a>
        </div>

        {{-- ══════════════ Tabla a lo ancho ══════════════ --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm
                    dark:border-gray-700 dark:bg-gray-800">



            @if ($ubicaciones->isEmpty())
                {{-- ══════ Estado vacío: todavía no hay registros ══════ --}}
                <div class="flex flex-col items-center justify-center gap-3 px-6 py-16 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600
                                dark:bg-amber-900/40 dark:text-amber-400">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V9m0 12c0-3-2.5-4.5-5-4.5M12 21c0-3 2.5-4.5 5-4.5M12 9c0-3-2-6-2-6s-2 3-2 6c0 1.7 1.8 3 4 3s4-1.3 4-3c0-3-2-6-2-6s-2 3-2 6Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Aún no hay Ubicaciones registradas</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Registra la primera para empezar a capturar entradas y salidas.</p>
                    </div>
                    {{-- TODO: cambia href="" por la ruta variedades.create cuando la tengas --}}
                    <a href=""
                       class="mt-2 inline-flex items-center gap-2 rounded-lg bg-amber-400 px-4 py-2 text-sm font-bold text-gray-900 transition hover:bg-amber-500
                              dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500">
                        Registrar la primera
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/95">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">

                              <th class="w-12 px-4 py-3">ID</th>

                                <th class="px-4 py-3">clave</th>
                                <th class="px-4 py-3">Ubicacion</th>
                                <th class="px-4 py-3">Direccion</th>
                                <th class="px-4 py-3 text-center">Estatus</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody x-ref="tbody" class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach ($ubicaciones  as $u)


                                <tr
                                    class="transition-colors hover:bg-emerald-50/60 dark:hover:bg-emerald-900/10">

                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $u->id }}
                                    </td>

                                     <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $u->clave }}
                                    </td>



                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $u->nombre }}
                                    </td>


                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $u->direccion }}
                                    </td>


                                    {{-- Estatus --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($u->activo)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700
                                                         dark:bg-emerald-900/40 dark:text-emerald-300">
                                                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-500
                                                         dark:bg-gray-700 dark:text-gray-400">
                                                <span class="inline-flex h-2 w-2 rounded-full bg-gray-400"></span>
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-1">
                                            {{-- TODO: cambia href="" por la ruta variedades.edit cuando la tengas --}}
                                            <a href="{{ route('ubicaciones.edit', $u) }}"
                                               class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100
                                                      dark:text-emerald-400 dark:hover:bg-emerald-900/30"
                                               title="Editar {{ $u->nombre }}">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.86 4.49 1.68-1.69a1.88 1.88 0 1 1 2.65 2.65L7.5 19.14l-4.5 1.36 1.36-4.5L16.86 4.49Z"/>
                                                </svg>
                                                Editar
                                            </a>
                                             <form method="POST"
                                                  action="{{ route('ubicaciones.toggle', $u) }}"
                                                  onsubmit="return confirm('¿{{ $u->activa ? 'Desactivar' : 'Activar' }} la variedad {{ $u->nombre }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                                                               {{ $u->activa
                                                                    ? 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700'
                                                                    : 'text-amber-600 hover:bg-amber-100 dark:text-amber-400 dark:hover:bg-amber-900/30' }}"
                                                        title="{{ $u->activa ? 'Desactivar' : 'Activar' }} {{ $u->nombre }}">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.64 5.64a9 9 0 1 0 12.72 0M12 3v9"/>
                                                    </svg>
                                                    {{ $u->activa ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
