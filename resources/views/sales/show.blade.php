@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Penjualan</h5>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 200px;">ID Transaksi</th>
                    <td>: #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>: {{ $sale->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Barang</th>
                    <td>: {{ $sale->product->nama_barang }} ({{ $sale->product->kode_barang }})</td>
                </tr>
                <tr>
                    <th>Qty</th>
                    <td>: {{ $sale->qty }} unit</td>
                </tr>
                <tr>
                    <th>Harga Satuan</th>
                    <td>: Rp {{ number_format($sale->product->harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td>: <strong class="text-success fs-5">Rp {{ number_format($sale->total_harga, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
