{{-- resources/views/components/table/stock-history-table.blade.php --}}
@props([
'title' => 'Riwayat Transaksi Keluar Masuk',
'viewAllRoute' => null,
'maxHeight' => 'max-h-80'
])

<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="grid grid-cols-2 items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
        @if($viewAllRoute)
        <a href="{{ $viewAllRoute }}" class="text-sm text-blue-600 hover:underline justify-self-end">Lihat Semua</a>
        @endif
    </div>
    <div class="overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 {{ $maxHeight }} scrollbar-hide">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Tipe</th>
                    <th class="px-6 py-3">Jumlah</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>