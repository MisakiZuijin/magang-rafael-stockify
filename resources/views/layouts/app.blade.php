<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>

</html>