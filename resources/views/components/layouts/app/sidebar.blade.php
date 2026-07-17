<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
    class="bg-sidebar text-sidebar-foreground sidebar-transition overflow-hidden h-screen shrink-0">

    <div class="h-full flex flex-col">

        <!-- Logo -->
        <div class="h-16 shrink-0 flex items-center border-b border-sidebar-border"
            :class="sidebarOpen ? 'px-4 gap-3' : 'justify-center'">
            <div class="w-9 h-9 shrink-0 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center">
                {{-- Cambia el ícono por tu logo: <img src="..." class="w-5 h-5"> --}}
                @svg('fas-seedling', 'w-4 h-4 text-gold')
            </div>
            <div x-show="sidebarOpen" x-transition class="leading-tight overflow-hidden">
                <div class="text-white font-bold text-base whitespace-nowrap">{{ config('app.name') }}</div>
                {{-- Subtítulo: cámbialo por el de tu empresa/sistema --}}
                <div class="text-[9px] tracking-[0.3em] uppercase text-sidebar-foreground whitespace-nowrap">
                    Salidas Electrónicas
                </div>
            </div>
        </div>

        <!-- Menú -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-6">
            <ul class="space-y-1 px-3">

                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-table-cells-large'
                    :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                <!-- Catalogos -->
<x-layouts.sidebar-two-level-link-parent title="Catálogos" icon="fas-folder-open"
    :active="request()->routeIs('Variedad*','ubicaciones*','inventario*')">

    <x-layouts.sidebar-two-level-link href="{{ route('Variedad.index') }}" icon='fas-seedling'
        :active="request()->routeIs('Variedad*')">Variedades</x-layouts.sidebar-two-level-link>


<x-layouts.sidebar-two-level-link href="{{ route('ubicaciones.index') }}" icon='fas-map'
        :active="request()->routeIs('ubicaciones*')">Ubicaciones</x-layouts.sidebar-two-level-link>
    {{-- Aquí luego agregas Ubicaciones cuando armes ese CRUD:
    <x-layouts.sidebar-two-level-link href="{{ route('ubicaciones.index') }}" icon='fas-warehouse'
        :active="request()->routeIs('ubicaciones*')">Ubicaciones</x-layouts.sidebar-two-level-link>
    --}}

    <x-layouts.sidebar-two-level-link href="{{ route('inventario.index') }}" icon='fas-boxes'
        :active="request()->routeIs('inventario*')">Inventario </x-layouts.sidebar-two-level-link>
</x-layouts.sidebar-two-level-link-parent>


  <!-- Entradas -->
<x-layouts.sidebar-two-level-link-parent title="Movimientos" icon="fas-seedling"
    :active="request()->routeIs('Movimientos','entradas*','salidas*')">

    <x-layouts.sidebar-two-level-link href="{{ route('entradas.index') }}" icon='fas-plus-circle'
        :active="request()->routeIs('entradas*')" >Entradas</x-layouts.sidebar-two-level-link>

  <x-layouts.sidebar-two-level-link href="{{ route('salidas.index') }}" icon='fas-minus-circle'
        :active="request()->routeIs('salidas*')">Salidas</x-layouts.sidebar-two-level-link>



    {{-- Aquí luego agregas Ubicaciones cuando armes ese CRUD:
    <x-layouts.sidebar-two-level-link href="{{ route('ubicaciones.index') }}" icon='fas-warehouse'
        :active="request()->routeIs('ubicaciones*')">Ubicaciones</x-layouts.sidebar-two-level-link>
    --}}

</x-layouts.sidebar-two-level-link-parent>

<!-- Movimientos (Padre) -->
















                {{-- Aquí van tus módulos, ejemplo:
                <x-layouts.sidebar-link href="{{ route('salidas.index') }}" icon='fas-truck'
                    :active="request()->routeIs('salidas*')">Salidas</x-layouts.sidebar-link>

                <x-layouts.sidebar-two-level-link-parent title="Catálogos" icon="fas-folder-open"
                    :active="request()->routeIs('almacenes*') || request()->routeIs('clientes*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('almacenes.index') }}" icon='fas-warehouse'
                        :active="request()->routeIs('almacenes*')">Almacenes</x-layouts.sidebar-two-level-link>
                </x-layouts.sidebar-two-level-link-parent>
                --}}

            </ul>
        </nav>

        <!-- Botón inferior amarillo -->
        <div class="p-4 shrink-0">
            {{-- Cambia href y texto por tu acción principal, ej. route('salidas.create') --}}
            <a href="{{ route('salidas.create') }}"
                class="flex items-center justify-center gap-2 w-full rounded-xl bg-gold hover:bg-gold-dark text-brand-dark font-bold text-sm py-3 transition-colors duration-200"
                :class="sidebarOpen ? 'px-4' : 'px-0'">
                @svg('fas-plus', 'w-4 h-4 shrink-0')
                <span     x-show="sidebarOpen" x-transition class="whitespace-nowrap">Nueva Salida</span>
            </a>
        </div>

    </div>
</aside>
