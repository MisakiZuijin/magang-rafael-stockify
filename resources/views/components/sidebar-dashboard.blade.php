@php
$url = explode('/', request()->url());
$page_slug = $url[count($url) - 2];
@endphp

<div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
    <ul class="space-y-2 font-medium">
        {{ $slot }}
    </ul>
</div>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>