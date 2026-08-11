<x-sidebar-dashboard>

    {{-- Logo / Brand --}}
    <li class="mb-4 px-3">
        <div class="flex items-center gap-3 py-2">
            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-800 dark:text-white">Dashboard Manager</span>
        </div>
    </li>

    @php
    $menus = [
    [
    'route' => 'manager.dashboard',
    'label' => 'Dashboard',
    'check' => 'manager.dashboard',
    'icon' => '<path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>',
    ],
    [
    'route' => 'manager.products',
    'label' => 'Produk',
    'check' => 'manager.products*',
    'icon' => '<path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>',
    ],
    [
    'route' => 'manager.stock',
    'label' => 'Stok',
    'check' => 'manager.stock*',
    'icon' => '<path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>',
    ],
    [
    'route' => 'manager.supplier',
    'label' => 'Supplier',
    'check' => 'manager.supplier*',
    'icon' => '<path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>',
    ],
    [
    'route' => 'manager.laporan',
    'label' => 'Laporan',
    'check' => 'manager.laporan',
    'icon' => '<path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"></path>',
    ],
    ];
    @endphp

    @foreach($menus as $menu)
    <li>
        <a href="{{ route($menu['route']) }}"
            class="flex items-center p-2 text-base font-medium rounded-lg group
           {{ request()->routeIs($menu['check']) ? 'bg-purple-100 text-purple-700 dark:bg-purple-700 dark:text-white' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <svg class="w-6 h-6 transition duration-75 flex-shrink-0
                {{ request()->routeIs($menu['check']) ? 'text-purple-700 dark:text-white' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}"
                fill="currentColor" viewBox="0 0 20 20">
                {!! $menu['icon'] !!}
            </svg>
            <span class="ml-3">{{ $menu['label'] }}</span>
        </a>
    </li>
    @endforeach

</x-sidebar-dashboard>