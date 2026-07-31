@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Pembelian</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Pembelian</h5>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 200px;">ID Transaksi</th>
                    <td>: #{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>: {{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Supplier</th>
                    <td>: {{ $purchase->supplier->nama_pic }} ({{ $purchase->supplier->no_supplier }})</td>
                </tr>
                <tr>
                    <th>Barang</th>
                    <td>: {{ $purchase->product->nama_barang }} ({{ $purchase->product->kode_barang }})</td>
                </tr>
                <tr>
                    <th>Qty</th>
                    <td>: {{ $purchase->qty }} unit</td>
                </tr>
                <tr>
                    <th>Harga Satuan</th>
                    <td>: Rp {{ number_format($purchase->product->harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td>: <strong class="text-success">Rp {{ number_format($purchase->total_harga, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
