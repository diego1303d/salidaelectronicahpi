<x-layouts.app>

   <head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Nueva Ubicación - Laravel Dashboard</title>
<!-- Tailwind CSS v3 CDN -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Google Fonts for Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<style data-purpose="custom-styles">
    body {
      font-family: 'Inter', sans-serif;
    }
    /* Main background gradient matching the mockup */
    .bg-main-gradient {
      background: radial-gradient(circle at 70% 30%, rgba(220, 245, 230, 0.6) 0%, rgba(255, 255, 255, 1) 60%),
                  radial-gradient(circle at 90% 90%, rgba(255, 235, 150, 0.4) 0%, transparent 50%),
                  radial-gradient(circle at 10% 10%, rgba(200, 230, 210, 0.3) 0%, transparent 40%);
      background-color: #f8fafc;
    }
    /* Frosted glass effect for the central card */
    .glass-card {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    /* Custom button shadow for "Guardar ubicación" */
    .btn-yellow-glow {
      background: linear-gradient(180deg, #fbdf67 0%, #facc15 100%);
      box-shadow: 0 4px 15px rgba(250, 204, 21, 0.4);
    }
    .sidebar-active {
      background-color: #f1f5f9;
      color: #1e293b;
    }
  </style>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            laravel: {
              dark: '#143424', // Dark green header
              sidebar: '#ffffff',
              accent: '#facc15', // Yellow
              muted: '#64748b'
            }
          }
        }
      }
    }
  </script>
</head>

<body class="bg-main-gradient min-h-screen flex" style="background: radial-gradient(circle at 80% 20%, rgba(220, 245, 230, 0.9) 0%, transparent 50%), radial-gradient(circle at 90% 80%, rgba(255, 235, 150, 0.7) 0%, transparent 50%), radial-gradient(circle at 20% 80%, rgba(240, 250, 245, 0.8) 0%, transparent 50%), radial-gradient(circle at 10% 20%, rgba(255, 245, 200, 0.5) 0%, transparent 40%); background-color: #f8fafc;">
    <div class="mx-auto w-full max-w-2xl space-y-6"
         x-data="{
            clave: {{ Js::from(old('clave', '')) }},
            nombre: {{ Js::from(old('nombre', '')) }},
            direccion: {{ Js::from(old('direccion', '')) }},
            activo: {{ old('activo', 1) ? 'true' : 'false' }}
         }">

      <main class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
<!-- BEGIN: Form Card -->
<div class="glass-card w-full max-w-3xl rounded-[2.5rem] shadow-2xl p-10 relative" data-purpose="form-card">
<h1 class="text-4xl font-bold text-laravel-dark mb-10">Nueva ubicación</h1>

        {{-- ══════════════ Formulario ══════════════ --}}
        <form method="POST" action="{{ route('ubicaciones.store') }}" class="space-y-8">
            @csrf





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
                            <p class="text-laravel-dark font-semibold"
                               x-text="activo ? 'Ubicación activa' : 'Ubicación inactiva'"></p>
                            <p class="text-xs text-laravel-muted">
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


            {{-- ══════════════ Acciones ══════════════ --}}
            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('ubicaciones.index') }}"
                   class="px-8 py-3 bg-slate-400 hover:bg-slate-500 text-white font-semibold rounded-xl transition-all shadow-md">
                    Cancelar
                </a>
                <button class="px-8 py-3 btn-yellow-glow text-laravel-dark font-semibold rounded-xl transition-all hover:opacity-90"  type="submit">

                    Guardar ubicación
                </button>
            </div>
        </form>
</div>
<!-- END: Form Card -->
</main>
<!-- END: Main Content -->


</body>

</x-layouts.app>
