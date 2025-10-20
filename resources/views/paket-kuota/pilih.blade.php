@extends('frontend.master')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center py-5">
            <div class="text-{{ $paket['warna'] }} mb-3">
                <i class="bi {{ $paket['ikon'] }} fs-1"></i>
            </div>
            <h3 class="fw-bold mb-2">{{ $paket['nama'] }}</h3>
            <p class="text-muted">{{ $paket['deskripsi'] }}</p>
            <hr>
            <p><strong>Kuota:</strong> {{ $paket['kuota'] }}</p>
            <p><strong>Kecepatan:</strong> {{ $paket['kecepatan'] }}</p>
            <p><strong>Masa Aktif:</strong> {{ $paket['masa_aktif'] }}</p>
            <h4 class="fw-bold text-dark my-3">Rp {{ number_format($paket['harga'], 0, ',', '.') }}</h4>
            <a href="#" class="btn btn-success px-4 rounded-pill">Konfirmasi Pembelian</a>
        </div>
    </div>
</div>
@endsection