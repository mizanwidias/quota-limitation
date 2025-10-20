@forelse ($services as $service)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <i class="bi bi-circle-fill text-primary me-2"></i>
            <span class="fw-semibold">{{ $service['asn'] ?? 'Unknown ASN' }}</span>
            <small class="text-muted ms-2">({{ $service['name'] ?? 'Unknown' }})</small>
        </div>
        <span class="fw-bold text-secondary">{{ formatBytes($service['total_bytes']) }}</span>
    </div>
@empty
    <p class="text-muted">Tidak ada data layanan yang tersedia.</p>
@endforelse
