@props(['title', 'icon', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="w-full">
    <!-- Botón principal que abre/cierra -->
    <button @click="open = !open" 
            type="button" 
            class="flex items-center justify-between w-full p-2 text-gray-900 rounded-lg hover:bg-gray-100 {{ $active ? 'bg-gray-100 font-semibold' : '' }}">
        <div class="flex items-center">
            <i class="{{ $icon }} mr-3"></i>
            <span>{{ $title }}</span>
        </div>
        <!-- Flecha que gira -->
        <i class="fas fa-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <!-- Contenedor de los sub-enlaces -->
    <div x-show="open" x-cloak class="pl-4 mt-1 space-y-1">
        {{ $slot }}
    </div>
</div>