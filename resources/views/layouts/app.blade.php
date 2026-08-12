<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>{{ ($settings['app_name'] ?? 'Stockify') . ' - Dashboard' }}</title>
    @stack('styles')
</head>

<body class="h-full bg-gray-100 dark:bg-gray-900">

    {{-- NAVBAR: h-16 exact, z-50 paling atas --}}
    @include('layouts.partials.navbar')

    {{-- SIDEBAR: top-16 (di bawah navbar), z-40 --}}
    <aside id="sidebar" class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] transition-transform -translate-x-full lg:translate-x-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-y-auto">
        @hasSection('sidebar')
        @yield('sidebar')
        @else
        @php
        $userRole = auth()->user()->role ?? 'Admin';
        $sidebarComponent = match($userRole) {
        'Manager Gudang' => 'components.sidebar.manager-sidebar',
        'Staff Gudang' => 'components.sidebar.staff-sidebar',
        default => 'components.sidebar.admin-sidebar',
        };
        @endphp
        @include($sidebarComponent)
        @endif
    </aside>

    {{-- OVERLAY: z-30, di bawah sidebar --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 hidden lg:hidden" aria-hidden="true"></div>

    {{-- KONTEN: mt-16 exact (tidak tertimbun navbar), ml-0 lg:ml-64 --}}
    <div class="lg:ml-64 mt-16 min-h-[calc(100vh-4rem)] bg-gray-50 dark:bg-gray-900">
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
        @include('layouts.partials.footer')
    </div>

    @stack('scripts')

    {{-- SCRIPT TOGGLE SIDEBAR MOBILE --}}
    <script>
        (function() {
            const toggleBtn = document.getElementById('toggleSidebarMobile');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const hamburgerIcon = document.getElementById('toggleSidebarMobileHamburger');
            const closeIcon = document.getElementById('toggleSidebarMobileClose');

            if (!toggleBtn || !sidebar) return;

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                if (overlay) overlay.classList.remove('hidden');
                if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
                toggleBtn.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }

            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isClosed = sidebar.classList.contains('-translate-x-full');
                isClosed ? openSidebar() : closeSidebar();
            });

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        })();
    </script>

    {{-- SCRIPT AJAX TABEL GLOBAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.data-table-wrapper').forEach(wrapper => {
                attachTableEvents(wrapper);
            });
        });

        function attachTableEvents(wrapper) {
            wrapper.querySelectorAll('.data-table-sort-link').forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    await loadTable(link.href, wrapper);
                });
            });

            const form = wrapper.querySelector('.data-table-search-form');
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const url = new URL(form.action || window.location.href);
                    const formData = new FormData(form);
                    formData.forEach((v, k) => {
                        if (v) url.searchParams.set(k, v);
                        else url.searchParams.delete(k);
                    });
                    await loadTable(url.toString(), wrapper);
                });
            }
        }

        async function loadTable(url, wrapper) {
            wrapper.style.opacity = '0.5';
            wrapper.style.transition = 'opacity 0.2s';
            try {
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newWrapper = doc.getElementById(wrapper.id);
                if (newWrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                    history.pushState({}, '', url);
                    attachTableEvents(wrapper);
                }
            } catch (err) {
                console.error('Gagal memuat tabel:', err);
                window.location.href = url;
            } finally {
                wrapper.style.opacity = '1';
            }
        }

        window.addEventListener('popstate', () => {
            window.location.reload();
        });
    </script>

</body>

</html>