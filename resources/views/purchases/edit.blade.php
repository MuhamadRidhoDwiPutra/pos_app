@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Pembelian</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Transaksi Pembelian</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('purchases.update', $purchase) }}" method="POST" id="purchaseForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_pic }} ({{ $supplier->no_supplier }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Barang</label>
                            <select name="product_id" id="product_id" class="form-select">
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-harga="{{ $product->harga }}"
                                        data-stock="{{ $product->stock }}"
                                        {{ old('product_id', $purchase->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama_barang }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="qty" class="form-label">Qty</label>
                            <input type="number" name="qty" id="qty" class="form-control" value="{{ old('qty', $purchase->qty) }}" min="1">
                            @error('qty')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" id="harga_satuan" class="form-control" readonly value="Rp 0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Total Harga</label>
                            <input type="text" id="total_harga" class="form-control fw-bold" readonly value="Rp 0">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success" id="btnSubmit">Perbarui</button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
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

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function hitungTotal() {
        const selected = productSelect.options[productSelect.selectedIndex];
        const harga = parseFloat(selected?.dataset?.harga) || 0;
        const qty = parseInt(qtyInput.value) || 0;

        hargaSatuan.value = formatRupiah(harga);
        totalHarga.value = formatRupiah(harga * qty);
    }

    productSelect.addEventListener('change', hitungTotal);
    qtyInput.addEventListener('input', hitungTotal);

    hitungTotal();
});
</script>
@endpush
