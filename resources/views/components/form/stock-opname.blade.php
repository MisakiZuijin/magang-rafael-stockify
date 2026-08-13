@props([
'products',
'action' => route('stock.opname')
])

<div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Stock Opname</h2>
    <form action="{{ $action }}" method="POST" class="grid grid-cols-12 gap-y-8" id="opname-form">
        @csrf

        <div class="col-span-12 relative">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk</label>

            <input type="text"
                id="product-search"
                list="product-list"
                autocomplete="off"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Ketik nama produk atau pilih dari daftar..."
                required>

            <datalist id="product-list">
                @foreach($products->sortBy('id') as $product)
                <option value="{{ $product->name }}"
                    data-id="{{ $product->id }}"
                    data-stock="{{ $product->stock }}"
                    data-display="{{ $product->name }} (Stock: {{ $product->stock }})">
                    {{ $product->name }} (Stock: {{ $product->stock }})
                </option>
                @endforeach
            </datalist>

            <input type="hidden" name="product_id" id="product-id">

            @error('product_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Fisik (Aktual)</label>
            <input type="number" name="actual_stock" id="actual-stock" min="0" required
                class="no-spinner bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Masukkan jumlah stock fisik">
            @error('actual_stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan (Opsional)</label>
            <input type="text" id="note-input" maxlength="255"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Contoh: Rusak, hilang, dll">
            <input type="hidden" name="notes" id="notes-hidden">
        </div>

        <button type="submit" class="col-span-5 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700">
            Simpan Opname
        </button>
    </form>
</div>

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('opname-form');
        const searchInput = document.getElementById('product-search');
        const hiddenId = document.getElementById('product-id');
        const hiddenNotes = document.getElementById('notes-hidden');
        const noteInput = document.getElementById('note-input');
        const actualInput = document.getElementById('actual-stock');
        const datalist = document.getElementById('product-list');
        const options = Array.from(datalist.querySelectorAll('option'));

        let selectedStock = 0;

        // Saat user memilih / mengetik
        searchInput.addEventListener('input', function() {
            const val = this.value.trim();
            const matched = options.find(opt => opt.value === val);

            if (matched) {
                hiddenId.value = matched.dataset.id;
                selectedStock = parseInt(matched.dataset.stock) || 0;
                searchInput.value = matched.dataset.display;
            } else {
                hiddenId.value = '';
                selectedStock = 0;
            }
        });

        // Sebelum submit, format notes
        form.addEventListener('submit', function(e) {
            if (!hiddenId.value) {
                e.preventDefault();
                alert('Silakan pilih produk dari daftar yang tersedia.');
                searchInput.focus();
                return;
            }

            const actualStock = parseInt(actualInput.value) || 0;
            const noteText = noteInput.value.trim();
            const selisih = Math.abs(selectedStock - actualStock);
            let keterangan = '';

            if (actualStock < selectedStock) {
                // Stock berkurang (rusak, hilang, dll)
                keterangan = `Dikeluarkan ${selisih}`;
            } else if (actualStock > selectedStock) {
                // Stock bertambah (lupa terhitung, ditemukan)
                keterangan = `Dimasukan ${selisih}`;
            } else {
                // Stock sesuai
                keterangan = `Sesuai`;
            }

            const formatted = `Stock Opname - ${noteText || '-'} | Jumlah Fisik ${actualStock} | ${keterangan}`;
            hiddenNotes.value = formatted;
        });
    })();
</script>
@endpush