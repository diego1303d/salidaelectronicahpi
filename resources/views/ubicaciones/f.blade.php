<x-layouts.app>

<!DOCTYPE html><html lang="es" style=""><head>
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
<!-- BEGIN: Sidebar -->

<!-- END: Sidebar -->
<!-- BEGIN: Main Content -->
<main class="flex-1 flex items-center justify-center p-8 relative overflow-hidden">
<!-- BEGIN: Form Card -->
<div class="glass-card w-full max-w-3xl rounded-[2.5rem] shadow-2xl p-10 relative" data-purpose="form-card">
<h1 class="text-4xl font-bold text-laravel-dark mb-10">Nueva ubicación</h1>



<form class="space-y-8">
<!-- Top Row: Clave & Nombre -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<!-- Clave Field -->
<div data-purpose="form-group">
<label class="block text-sm font-semibold text-gray-700 mb-2">Clave</label>
<input class="w-full rounded-lg py-3 px-4 focus:ring-laravel-dark focus:border-laravel-dark shadow-sm border-gray-200" type="text" value="BOD">
<p class="text-xs text-gray-400 mt-2">0/10 caracteres</p>
</div>
<!-- Nombre Field -->
<div data-purpose="form-group">
<label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de la ubicación</label>
<input class="w-full rounded-lg py-3 px-4 focus:ring-laravel-dark focus:border-laravel-dark shadow-sm border-gray-200" placeholder="Ej. Bodega Villagrán" type="text">
</div>
</div>



<!-- Dirección (Optional) -->
<div data-purpose="form-group">
<label class="block text-sm font-semibold text-gray-700 mb-2">Dirección <span class="font-normal text-gray-500">(opcional)</span></label>
<textarea class="w-full rounded-lg py-3 px-4 focus:ring-laravel-dark focus:border-laravel-dark shadow-sm resize-none border-gray-200" placeholder="Calle, número, colonia, municipio..." rows="4"></textarea>
</div>
<!-- Ubicación Activa Toggle Section -->
<div data-purpose="toggle-section">
<label class="block text-sm font-semibold text-gray-700 mb-2">Ubicación activa</label>
<div class="bg-white/80 rounded-2xl p-5 flex items-center gap-4 shadow-sm border border-gray-100">
<!-- Custom Toggle -->
<button class="relative inline-flex items-center h-10 w-20 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-laravel-dark" type="button">
<span class="sr-only">Toggle active status</span>
<!-- Box Icon inside toggle -->
<span class="absolute left-2 text-white/40">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</span>
<span aria-hidden="true" class="pointer-events-none inline-block h-8 w-8 transform translate-x-10 rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
</button>
<div class="flex flex-col">
<span class="text-laravel-dark font-semibold">Ubicación activa</span>
<span class="text-xs text-laravel-muted">Solo las activas aparecen al capturar entradas y salidas.</span>
</div>
</div>
</div>
<!-- Form Actions -->
<div class="flex justify-end gap-4 pt-4">
<button class="px-8 py-3 bg-slate-400 hover:bg-slate-500 text-white font-semibold rounded-xl transition-all shadow-md" type="button">
            Cancelar
          </button>
<button class="px-8 py-3 btn-yellow-glow text-laravel-dark font-semibold rounded-xl transition-all hover:opacity-90" type="submit">
            Guardar ubicación
          </button>
</div>
</form>
</div>
<!-- END: Form Card -->
</main>
<!-- END: Main Content -->


</body></html>

</x-layouts.app>
