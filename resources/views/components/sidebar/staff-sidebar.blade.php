<x-sidebar-dashboard>

    {{-- Logo / Brand --}}
    <li class="mb-4 px-3">
        <div class="flex items-center gap-3 py-2">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-800 dark:text-white">Staff Gudang</span>
        </div>
    </li>

    @php
    $menus = [
    [
    'route' => 'staff.dashboard',
    'label' => 'Dashboard',
    'check' => 'staff.dashboard',
    'icon' => '<path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>',
    ],
    [
    'route' => 'staff.stock',
    'label' => 'Konfirmasi Stock',
    'check' => 'staff.stock*',
    'icon' => '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>',
    ],
    ];
    @endphp

    @foreach($menus as $menu)
    <li>
        <a href="{{ route($menu['route']) }}"
            class="flex items-center p-2 text-base font-medium rounded-lg group
           {{ request()->routeIs($menu['check']) ? 'bg-orange-100 text-orange-700 dark:bg-orange-700 dark:text-white' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <svg class="w-6 h-6 transition duration-75 flex-shrink-0
                {{ request()->routeIs($menu['check']) ? 'text-orange-700 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                fill="currentColor" viewBox="0 0 20 20">
                {!! $menu['icon'] !!}
            </svg>
            <span class="ml-3">{{ $menu['label'] }}</span>
        </a>
    </li>
    @endforeach

</x-sidebar-dashboard>