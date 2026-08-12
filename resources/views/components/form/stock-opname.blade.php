@props([
'products',
'action' => route('stock.opname') // default ke admin
])

<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stock Opname</h2>
    <form action="{{ $action }}" method="POST" class="grid grid-cols-12 gap-y-8">
        @csrf

        <div class="col-span-12">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk</label>
            <select name="product_id" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Pilih Produk --</option>
                @foreach($products->sortBy('id') as $product)
                <option value="{{ $product->id }}">
                    {{ $product->name }} (Stock: {{ $product->stock }})
                </option>
                @endforeach
            </select>
            @error('product_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Fisik (Aktual)</label>
            <input type="number" name="actual_stock" min="0" required
                class="no-spinner bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Masukkan jumlah stock fisik">
            @error('actual_stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan (Opsional)</label>
            <input type="text" name="notes" maxlength="255"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Contoh: Rusak, hilang, dll">
        </div>
        <button type="submit" class="col-span-5 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
            Simpan Opname
        </button>
    </form>
</div>