<x-layouts.app>

    <div class="w-full space-y-6"
         x-data="{
            buscar: '',
            visibles: {{ $variedades->count() }},
            filtrar() {
                const q = this.buscar.trim().toLowerCase();
                let n = 0;
                this.$refs.tbody.querySelectorAll('tr[data-nombre]').forEach(tr => {
                    const ok = tr.dataset.nombre.includes(q);
                    tr.style.display = ok ? '' : 'none';
                    if (ok) n++;
                });
                this.visibles = n;
            }
         }">

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
                    Variedades de trigo
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Administra las variedades y su peso por bulto para validar entradas y salidas.
                </p>
            </div>

            {{-- Botón dorado: mismo acento que "Nueva Salida", contrasta en tema claro y oscuro --}}
            {{-- TODO: cambia href="" por la ruta variedades.create cuando la tengas --}}
            <a  href="{{ route('Variedad.create') }}"
               class="inline-flex items-center gap-2 self-start rounded-lg bg-gold px-4 py-2.5 text-sm font-bold text-brand-dark shadow-sm
       transition hover:bg-gold-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-2
       dark:bg-emerald-600 dark:text-white dark:hover:bg-emerald-500 dark:focus-visible:ring-emerald-500 dark:focus-visible:ring-offset-gray-900">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nueva variedad
            </a>
        </div>

        {{-- ══════════════ Tabla a lo ancho ══════════════ --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm
                    dark:border-gray-700 dark:bg-gray-800">

            {{-- Barra de herramientas: buscador + contador en vivo --}}
            <div class="flex flex-col gap-3 border-b border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between
                        dark:border-gray-700">
                <div class="relative w-full sm:max-w-xs">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/>
                    </svg>
                    <input type="text"
                           x-model="buscar"
                           @input.debounce.150ms="filtrar()"
                           placeholder="       Buscar variedad..."
                           class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-9 text-sm text-gray-900 placeholder-gray-400
                                  transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30
                                  dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    {{-- Botón para limpiar la búsqueda --}}
                    <button type="button"
                            x-show="buscar.length > 0"
                            x-cloak
                            @click="buscar = ''; filtrar()"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-full p-0.5 text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                    Mostrando <span class="font-bold text-emerald-700 dark:text-emerald-400" x-text="visibles"></span>
                    de {{ $variedades->count() }} variedades
                </p>
            </div>

            @if ($variedades->isEmpty())
                {{-- ══════ Estado vacío: todavía no hay registros ══════ --}}
                <div class="flex flex-col items-center justify-center gap-3 px-6 py-16 text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600
                                dark:bg-amber-900/40 dark:text-amber-400">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V9m0 12c0-3-2.5-4.5-5-4.5M12 21c0-3 2.5-4.5 5-4.5M12 9c0-3-2-6-2-6s-2 3-2 6c0 1.7 1.8 3 4 3s4-1.3 4-3c0-3-2-6-2-6s-2 3-2 6Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">Aún no hay variedades registradas</p>
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
                            <tr class="border-b border-gray-200 text-left text-xs uppercase tracking-wide
                           text-gray-500 dark:border-gray-700 dark:text-gray-400">

                              <th class="w-12 px-4 py-3">ID</th>
                                <th class="px-4 py-3">Variedad</th>
                                <th class="px-4 py-3">Categoría</th>
                                <th class="px-4 py-3 text-right">Peso por bulto</th>
                                <th class="px-4 py-3 text-right">Bultos por tonelada</th>
                                <th class="px-4 py-3 text-center">Estatus</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody x-ref="tbody" class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach ($variedades as $v)


                                <tr data-nombre="{{ mb_strtolower($v->nombre) }}"
                                    class="transition-colors hover:bg-emerald-50/60 dark:hover:bg-emerald-900/10">

    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $v->id }}
                                    </td>
                                    {{-- Nombre con avatar de inicial --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700
                                                         dark:bg-emerald-900/40 dark:text-emerald-400">
                                                {{ mb_strtoupper(mb_substr($v->nombre, 0, 1)) }}
                                            </span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $v->nombre }}</span>
                                        </div>
                                    </td>

                                    {{-- Categoría --}}
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ $v->categoria }}
                                    </td>

                                    {{-- Peso por bulto --}}
                                    <td class="px-4 py-3 text-right font-medium tabular-nums text-gray-700 dark:text-gray-300">
                                        {{ number_format($v->peso_bulto_kg, 2) }} <span class="text-xs text-gray-400">kg</span>
                                    </td>

                                    {{-- Bultos por tonelada (calculado, útil para validar capturas) --}}
                                    <td class="px-4 py-3 text-right tabular-nums text-gray-500 dark:text-gray-400">
                                        {{ $v->peso_bulto_kg > 0 ? '≈ ' . number_format(1000 / $v->peso_bulto_kg, 0) : '—' }}
                                    </td>

                                    {{-- Estatus --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($v->activo)
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
                                            <a href="{{ route('Variedad.edit', $v) }}"
                                               class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100
                                                      dark:text-emerald-400 dark:hover:bg-emerald-900/30"
                                               title="Editar {{ $v->nombre }}">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.86 4.49 1.68-1.69a1.88 1.88 0 1 1 2.65 2.65L7.5 19.14l-4.5 1.36 1.36-4.5L16.86 4.49Z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('variedades.toggle', $v) }}"
                                                  onsubmit="return confirm('¿{{ $v->activa ? 'Desactivar' : 'Activar' }} la variedad {{ $v->nombre }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                                                               {{ $v->activa
                                                                    ? 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700'
                                                                    : 'text-amber-600 hover:bg-amber-100 dark:text-amber-400 dark:hover:bg-amber-900/30' }}"
                                                        title="{{ $v->activa ? 'Desactivar' : 'Activar' }} {{ $v->nombre }}">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.64 5.64a9 9 0 1 0 12.72 0M12 3v9"/>
                                                    </svg>
                                                    {{ $v->activa ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- ══════ Sin coincidencias de búsqueda ══════ --}}
                            <tr x-show="visibles === 0" x-cloak>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Sin coincidencias</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        No hay variedades que coincidan con
                                        "<span class="font-semibold text-emerald-700 dark:text-emerald-400" x-text="buscar"></span>".
                                    </p>
                                    <button type="button"
                                            @click="buscar = ''; filtrar()"
                                            class="mt-3 text-sm font-semibold text-emerald-700 underline-offset-2 hover:underline dark:text-emerald-400">
                                        Limpiar búsqueda
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
