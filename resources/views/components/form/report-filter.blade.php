{{-- resources/views/components/form/report-filter.blade.php --}}
@props([
'filters' => [],
'categories' => [],
'action' => '#'
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">
    <form action="{{ $action }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
        {{-- Dari Tanggal --}}
        <div class="min-w-0">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        {{-- Sampai Tanggal --}}
        <div class="min-w-0">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        {{-- Kategori --}}
        <div class="min-w-0">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
            <select name="category_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none scrollbar-hide">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Tipe Transaksi --}}
        <div class="min-w-0">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe Transaksi</label>
            <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Semua</option>
                <option value="Masuk" {{ ($filters['type'] ?? '') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                <option value="Keluar" {{ ($filters['type'] ?? '') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
            </select>
        </div>

        {{-- Status Stok --}}
        <div class="min-w-0">
            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status Stok</label>
            <select name="stock_status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Semua</option>
                <option value="aman" {{ ($filters['stock_status'] ?? '') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                <option value="kritis" {{ ($filters['stock_status'] ?? '') == 'kritis' ? 'selected' : '' }}>Stok Kritis</option>
                <option value="habis" {{ ($filters['stock_status'] ?? '') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
            </select>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-2 min-w-0">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium px-3 py-2 rounded-lg transition">
                Terapkan
            </button>
            <a href="{{ $action }}" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 text-xs sm:text-sm font-medium px-3 py-2 rounded-lg transition text-center">
                Reset
            </a>
        </div>
    </form>
</div>