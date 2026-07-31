@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Transaksi Penjualan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.update', $sale) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Barang</label>
                            <select name="product_id" id="product_id" class="form-select">
                                <option value="">-- Pilih Barang --</option>
                                @forelse ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-harga="{{ $product->harga }}"
                                        data-stock="{{ $product->stock }}"
                                        {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama_barang }} (Stock: {{ $product->stock }})
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada barang tersedia.</option>
                                @endforelse
                            </select>
                            @error('product_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="qty" class="form-label">Qty</label>
                            <input type="number" name="qty" id="qty" class="form-control" value="{{ old('qty', $sale->qty) }}" min="1">
                            <div id="stockWarning" class="text-danger small mt-1 d-none"></div>
                            @error('qty')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" id="harga_satuan" class="form-control" readonly value="Rp 0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Total Harga</label>
                            <input type="text" id="total_harga" class="form-control fw-bold text-success" readonly value="Rp 0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Stok Tersedia</label>
                            <input type="text" id="stok_tersedia" class="form-control" readonly value="0">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success" id="btnSubmit">Perbarui</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product_id');
    const qtyInput = document.getElementById('qty');
    const hargaSatuan = document.getElementById('harga_satuan');
    const totalHarga = document.getElementById('total_harga');
    const stokTersedia = document.getElementById('stok_tersedia');
    const stockWarning = document.getElementById('stockWarning');
    const btnSubmit = document.getElementById('btnSubmit');

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function hitungTotal() {
        const selected = productSelect.options[productSelect.selectedIndex];
        const harga = parseFloat(selected?.dataset?.harga) || 0;
        const stock = parseInt(selected?.dataset?.stock) || 0;
        const qty = parseInt(qtyInput.value) || 0;

        hargaSatuan.value = formatRupiah(harga);
        totalHarga.value = formatRupiah(harga * qty);
        stokTersedia.value = stock;

        if (qty > stock && stock > 0) {
            stockWarning.textContent = 'Stok tidak mencukupi! Stok tersedia: ' + stock;
            stockWarning.classList.remove('d-none');
            btnSubmit.disabled = true;
        } else {
            stockWarning.classList.add('d-none');
            btnSubmit.disabled = false;
        }

        if (stock === 0) {
            stockWarning.textContent = 'Stok habis!';
            stockWarning.classList.remove('d-none');
        }
    }

    productSelect.addEventListener('change', hitungTotal);
    qtyInput.addEventListener('input', hitungTotal);

    hitungTotal();
});
</script>
@endpush
