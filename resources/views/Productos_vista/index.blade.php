



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1b4332',
                        'on-primary': '#ffffff',
                        surface: '#fbf8ff',
                        'on-surface': '#191b23',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f4f2ff',
                        'surface-container-high': '#e9e7f4',
                        'on-surface-variant': '#444653',
                        outline: '#757784',
                        'outline-variant': '#c5c6d5',
                        secondary: '#516350',
                        error: '#ba1a1a',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'xl': '12px',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }
    </style>
</head>
<body class="bg-surface text-on-surface">

<x-layouts.app>
    <div class="p-6 lg:p-10 space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-primary font-display">Gestión de Inventario de Trigo</h1>
                <p class="text-on-surface-variant text-base">Monitoreo técnico y logístico de silos en tiempo real.</p>
            </div>
            <div class="flex gap-3">
                <button class="flex items-center gap-2 px-4 py-2 border border-outline rounded-lg hover:bg-surface-container-low transition-colors text-on-surface font-medium">
                    <span class="material-symbols-outlined">download</span>
                    Exportar Reporte
                </button>
                <button class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg hover:opacity-90 transition-opacity shadow-sm font-medium">
                    <span class="material-symbols-outlined">filter_list</span>
                    Filtros Avanzados
                </button>
            </div>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="p-2 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </span>
                    <span class="text-xs font-bold text-on-surface-variant tracking-wider uppercase">TOTAL</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">124,580 <span class="text-sm font-normal text-on-surface-variant">MT</span></div>
                <div class="text-sm text-on-surface-variant mt-1">Tonelaje total almacenado</div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="p-2 bg-secondary/10 rounded-lg text-secondary">
                        <span class="material-symbols-outlined">payments</span>
                    </span>
                    <span class="text-xs font-bold text-on-surface-variant tracking-wider uppercase"></span>
                </div>
                <div class="text-2xl font-bold text-on-surface"> <span class="text-sm font-normal text-on-surface-variant"></span></div>
                <div class="text-sm text-on-surface-variant mt-1">Requis</div>
            </div>

            <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <span class="p-2 bg-error/10 rounded-lg text-error">
                        <span class="material-symbols-outlined">warning</span>
                    </span>
                    <span class="text-xs font-bold text-on-surface-variant tracking-wider uppercase">ALERTAS</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">4</div>
                <div class="text-sm text-on-surface-variant mt-1">Revisiones técnicas urgentes</div>
            </div>
        </div>

        <!-- Inventory Table Section -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="p-6 border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="font-bold text-lg text-on-surface">Registro Detallado de Inventario</h3>

            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-low text-xs text-on-surface-variant uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">Nombre</th>
                            <th class="px-6 py-4 font-bold">Descripccion</th>
                            <th class="px-6 py-4 font-bold">Grado</th>
                            <th class="px-6 py-4 font-bold text-right">Unidad</th>
                            <th class="px-6 py-4 font-bold text-right">Cantidad (MT) </th>


                            <th class="px-6 py-4 font-bold text-center">Estado</th>
                        </tr>
                    </thead>

                   @forelse ( $productos as $productos )


                    <tbody class="divide-y divide-outline-variant text-sm">
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-on-surface">{{ $productos->nombre }}</td>
                            <td class="px-6 py-4 text-on-surface-variant leading-tight">{{ $productos->descripcion }}</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 rounded text-[10px] font-bold">{{ $productos->unidad_base}}</span></td>
                            <td class="px-6 py-4 text-right font-semibold">{{$productos->unidad_mayor}}</td>
                            <td class="px-6 py-4 text-right">{{$productos->stock_actual}}</td>


                            <td class="px-6 py-4 text-center"><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-[10px] font-bold">{{$productos->activo}}</span></td>
                        </tr>


                            @empty

                   @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-surface-container-low flex justify-between items-center text-sm text-on-surface-variant border-t border-outline-variant">
                <span>Mostrando 4 de 24 silos activos</span>
                <div class="flex gap-2">
                    <button class="w-8 h-8 flex items-center justify-center border border-outline-variant rounded hover:bg-surface-container-lowest transition-colors"><span class="material-symbols-outlined text-sm">chevron_left</span></button>
                    <button class="w-8 h-8 flex items-center justify-center bg-primary text-on-primary rounded font-bold">1</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-outline-variant rounded hover:bg-surface-container-lowest transition-colors font-bold">2</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-outline-variant rounded hover:bg-surface-container-lowest transition-colors font-bold">3</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-outline-variant rounded hover:bg-surface-container-lowest transition-colors"><span class="material-symbols-outlined text-sm">chevron_right</span></button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

</body>
</html>



