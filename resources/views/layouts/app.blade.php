<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/settings/favicon.ico') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>Dashboard</title>
    @stack('styles')
</head>

<body class="h-full bg-gray-100 dark:bg-gray-900">

    @include('layouts.partials.navbar')

    @yield('sidebar', View::make('components.sidebar.admin-sidebar'))

    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" id="sidebar-overlay"></div>

    <!-- Konten -->
    <div class="lg:ml-64 pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
        @include('layouts.partials.footer')
    </div>

    @stack('scripts')

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