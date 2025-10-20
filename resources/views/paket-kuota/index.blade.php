@extends('frontend.master')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Pilih Paket Kuota Internet</h4>
    <div class="row g-4">
        
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-4">
                        <div class="text mb-3">
                            <i class="bi fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Kuota 20GB</h5>
                        <p class="text-muted small">IYA INTERNET NIH</p>
                        <ul class="list-unstyled small text-secondary mb-3">
                            <li><i class="bi bi-hdd-network me-1">Kuota Bulan Madu</i> </li>
                            <li><i class="bi bi-speedometer2 me-1">15Mbps</i> </li>
                            <li><i class="bi bi-calendar3 me-1">1 Bulan</i></li>
                        </ul>
                        <h5 class="fw-bold text-dark mb-3">Rp 50.000,00</h5>
                        <a href="#" class="btn btn-primary rounded-pill">
                            Pilih Paket
                        </a>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection 