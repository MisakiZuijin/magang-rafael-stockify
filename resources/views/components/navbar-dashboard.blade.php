<nav class="fixed z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <!-- Toggle Sidebar Mobile -->
                <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <!-- Logo & Nama Aplikasi (Dinamis) -->
                <a href="{{ url('/') }}" class="flex ml-2 md:mr-24 items-center gap-2">
                    @if(!empty($settings['app_logo']) && file_exists(public_path($settings['app_logo'])))
                    <img src="{{ asset($settings['app_logo']) }}" alt="Logo" class="h-8 w-auto object-contain">
                    @else
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                    </svg>
                    @endif
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                        {{ $settings['app_name'] ?? 'Stockify' }}
                    </span>
                </a>

                <!-- Search -->
                <form action="#" method="GET" class="hidden lg:block lg:pl-3.5">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative mt-1 lg:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="text" name="email" id="topbar-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search">
                    </div>
                </form>
            </div>

            <!-- Right Side Items -->
            <div class="flex items-center gap-3">
                <!-- Search Mobile -->
                <button id="toggleSidebarMobileSearch" type="button" class="p-2 text-gray-500 rounded-lg lg:hidden hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <span class="sr-only">Search</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </button>

                {{-- Nama & Role — akan hide saat dropdown aktif --}}
                <div id="user-info" class="hidden sm:grid grid-cols-1 text-right transition-all duration-200 ease-in-out">
                    <p class="text-base font-bold text-gray-900 dark:text-white leading-tight">
                        {{ auth()->user()->name ?? 'Guest' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ auth()->user()->role ?? '' }}
                    </p>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button type="button" id="user-menu-button-2" data-dropdown-toggle="dropdown-2" data-dropdown-offset-skidding="0" data-dropdown-placement="bottom-end"
                        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-transform duration-200">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=0D8ABC&color=fff" alt="user photo">
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown-2" class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600 min-w-[12rem]">
                        <div class="px-4 py-3">
                            <p class="text-sm text-gray-900 dark:text-white font-semibold">
                                {{ auth()->user()->name ?? 'Guest' }}
                            </p>
                            <p class="text-sm text-gray-500 truncate dark:text-gray-300">
                                {{ auth()->user()->email ?? '' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5 dark:text-gray-400">
                                {{ auth()->user()->role ?? '' }}
                            </p>
                        </div>
                        <ul class="py-1">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white transition-colors">
                                        Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Script toggle nama/role --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const info = document.getElementById('user-info');
        const dropdown = document.getElementById('dropdown-2');
        if (!info || !dropdown) return;

        const observer = new MutationObserver(function() {
            if (dropdown.classList.contains('hidden')) {
                info.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                info.classList.add('opacity-100', 'scale-100');
            } else {
                info.classList.remove('opacity-100', 'scale-100');
                info.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            }
        });
        observer.observe(dropdown, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
</script>