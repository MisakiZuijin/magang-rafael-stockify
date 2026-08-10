<footer class="fixed bottom-0 z-50 w-full bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col sm:flex-row justify-between items-center p-4 gap-2">
        <p class="text-sm text-gray-500 dark:text-gray-400 text-center sm:text-left">
            &copy; {{ date('Y') }} {{ $settings['company_name'] ?? ($settings['app_name'] ?? 'Stockify') }}. All rights reserved.
        </p>

        @if(!empty($settings['company_phone']) || !empty($settings['company_email']))
        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
            @if(!empty($settings['company_phone']))
            <span class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                {{ $settings['company_phone'] }}
            </span>
            @endif
            @if(!empty($settings['company_email']))
            <span class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ $settings['company_email'] }}
            </span>
            @endif
        </div>
        @endif
    </div>
</footer>