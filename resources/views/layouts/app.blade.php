<!DOCTYPE html>
<html lang="en">

<head class="h-full bg-gray-100 dark:bg-gray-900">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <title>Dashboard</title>


</head>

@stack('scripts')

<body class="bg-gray-100 dark:bg-gray-900">
    @include('layouts.partials.navbar')
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        @include('layouts.partials.sidebar')
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main>
                @yield('content')
            </main>
            @include('layouts.partials.footer')
        </div>
    </div>
</body>

</html>