<x-sidebar-dashboard>

    {{-- Logo / Brand --}}
    <li class="mb-4 px-3">
        <div class="flex items-center gap-3 py-2">
            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-800 dark:text-white">Dashboard Staff</span>
        </div>
    </li>

    @php
    $menus = [
    ['route' => 'staff.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ];
    @endphp

    @foreach($menus as $menu)
    <li>
        <a href="{{ route($menu['route']) }}"
            class="flex items-center p-2 text-base font-medium rounded-lg group
           {{ request()->routeIs($menu['route']) ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700 dark:text-white' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <svg class="w-6 h-6 transition duration-75 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}" />
            </svg>
            <span class="ml-3">{{ $menu['label'] }}</span>
        </a>
    </li>
    @endforeach

</x-sidebar-dashboard>