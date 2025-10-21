@extends('frontend.master')

@section('content')
<div class="container py-5">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Pilih Paket Kuota Internet</h2>
        <p class="text-muted">Temukan paket internet yang sesuai dengan kebutuhan Anda</p>
    </div>

    <!-- Paket Cards -->
    <div class="row g-4">
        @forelse($pakets as $paket)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 paket-card position-relative">
                    <!-- Badge -->
                    @if($paket['badge'])
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-{{ $paket['warna'] }} rounded-pill px-3 py-2">
                                {{ $paket['badge'] }}
                            </span>
                        </div>
                    @endif

                    <div class="card-body text-center py-4 d-flex flex-column">
                        <!-- Icon -->
                        <div class="text-{{ $paket['warna'] }} mb-3">
                            <i class="bi {{ $paket['ikon'] }} fs-1"></i>
                        </div>

                        <!-- Nama Paket -->
                        <h5 class="fw-bold mb-2">{{ $paket['nama'] }}</h5>
                        <p class="text-muted small mb-3">{{ $paket['deskripsi'] }}</p>

                        <!-- Spesifikasi -->
                        <ul class="list-unstyled small text-secondary mb-3 text-start">
                            <li class="mb-2">
                                <i class="bi bi-hdd-network text-{{ $paket['warna'] }} me-2"></i>
                                <strong>Kuota:</strong> {{ $paket['kuota'] }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-speedometer2 text-{{ $paket['warna'] }} me-2"></i>
                                <strong>Kecepatan:</strong> {{ $paket['kecepatan'] }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-calendar3 text-{{ $paket['warna'] }} me-2"></i>
                                <strong>Masa Aktif:</strong> {{ $paket['masa_aktif'] }}
                            </li>
                        </ul>

                        <!-- Fitur -->
                        @if(isset($paket['fitur']))
                            <div class="mb-3">
                                <hr class="my-3">
                                <div class="text-start">
                                    @foreach($paket['fitur'] as $fitur)
                                        <small class="d-block mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                                            {{ $fitur }}
                                        </small>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Spacer untuk push button ke bawah -->
                        <div class="mt-auto">
                            <!-- Harga -->
                            <h4 class="fw-bold text-{{ $paket['warna'] }} mb-3">
                                Rp {{ number_format($paket['harga'], 0, ',', '.') }}
                            </h4>

                            <!-- Button -->
                            <a href="{{ route('kuota.pilih', $paket['id']) }}" 
                               class="btn btn-{{ $paket['warna'] }} rounded-pill px-4 w-100">
                                <i class="bi bi-cart-plus me-2"></i>
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted">Tidak ada paket tersedia saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Info Banner -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 bg-light rounded-4">
                <div class="card-body text-center py-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Informasi Penting
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <i class="bi bi-shield-check text-success fs-3 mb-2 d-block"></i>
                            <small class="fw-semibold">Aman & Terpercaya</small>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-headset text-primary fs-3 mb-2 d-block"></i>
                            <a href="https://wa.me/6289699405414">hubungi</a>
                            <small class="fw-semibold">Customer Service 24/7</small>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-lightning-charge text-warning fs-3 mb-2 d-block"></i>
                            <small class="fw-semibold">Aktivasi Instan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.paket-card {
    transition: all 0.3s ease;
    border: 2px solid transparent !important;
}

.paket-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    border-color: var(--bs-primary) !important;
}

.paket-card .btn {
    transition: all 0.3s ease;
}

.paket-card:hover .btn {
    transform: scale(1.05);
}
</style>
@endsection