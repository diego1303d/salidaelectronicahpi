@props(['active' => false, 'href' => '#', 'icon' => null])
<li>
    <a href="{{ $href }}" @class([
        'relative flex items-center text-sm rounded-lg px-3 py-2.5 transition-colors duration-200',
        'bg-sidebar-accent text-white font-bold' => $active,
        'text-sidebar-foreground hover:bg-white/5 hover:text-white font-medium' => !$active,
    ])
    :class="{ 'justify-center': !sidebarOpen, 'justify-start': sidebarOpen }">

        @if ($active)
            <!-- Barrita amarilla del item activo -->
            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-1 rounded-r-full bg-gold"></span>
        @endif

        @svg($icon, $active ? 'w-5 h-5 shrink-0 text-white' : 'w-5 h-5 shrink-0 text-sidebar-foreground')

        <span x-show="sidebarOpen" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0 transform -translate-x-2"
            x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition-all duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform -translate-x-2"
            class="ml-3 whitespace-nowrap">{{ $slot }}</span>
    </a>
</li>