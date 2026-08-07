<x-sidebar-dashboard>
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-40 w-64 h-screen bg-gray-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 -translate-x-full" id="sidebar">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-800">
            <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-lg font-bold tracking-tight">AdminPanel</span>
        </div>

        <!-- Navigation -->
        <nav class="px-4 py-4 space-y-1 overflow-y-auto h-[calc(100vh-80px)]">

            <!-- Dashboard -->
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Product -->
            <a href="/products" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('products*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Produk</span>
            </a>

            <!-- Transaksi -->
            <a href="/transactions" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('transactions*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Transaksi</span>
            </a>

            <!-- Divider -->
            <div class="my-4 border-t border-gray-800"></div>

            <!-- Users -->
            <a href="/users" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('users*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <span>Pengguna</span>
            </a>
        </nav>
    </aside>

    <!-- Overlay mobile -->
    <div class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Toggle button mobile -->
    <button class="fixed top-4 left-4 z-50 lg:hidden bg-gray-900 text-white p-2 rounded-lg" onclick="toggleSidebar()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</x-sidebar-dashboard>