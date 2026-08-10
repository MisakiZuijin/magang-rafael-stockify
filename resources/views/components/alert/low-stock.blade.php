{{-- resources/views/components/alert/low-stock.blade.php --}}
@props(['count'])

<div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <span class="font-medium">Perhatian!</span> Terdapat {{ $count }} produk dengan stok di bawah minimum.
</div>