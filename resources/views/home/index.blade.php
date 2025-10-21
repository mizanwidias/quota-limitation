@extends('frontend.master')

@section('chart')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Tren Pemakaian Kuota</h4>
            </div>
            <div class="card-body">
                <div id="chart-profile-visit"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('serviceCharts')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-0 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                    TOP Services (AS)
                </h5>
                <select id="timeRange" class="form-select form-select-sm w-auto shadow-sm">
                    <option value="h" selected>1 Jam</option>
                    <option value="d">Hari Ini</option>
                    <option value="w">1 Minggu</option>
                    <option value="m">1 Bulan</option>
                    <option value="y">1 Tahun</option>
                </select>
            </div>

            <div class="card-body">
                <div style="position: relative; min-height: 350px;">
                    <div id="chartLoading" class="loading-overlay" style="display: none;">
                        <div class="d-flex flex-column align-items-center justify-content-center h-100">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted">Memuat data...</p>
                        </div>
                    </div>
                    <canvas id="topServiceChart" height="120"></canvas>
                </div>

                <hr class="my-4">

                <div id="serviceList">
                    @forelse ($services as $index => $service)
                        <div class="service-item d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 bg-light bg-opacity-50 hover-highlight">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="service-rank me-3">
                                    <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark mb-1">
                                        <i class="bi bi-hdd-network text-primary me-2"></i>
                                        {{ $service['asn'] ?? 'Unknown ASN' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $service['name'] ?? 'Unknown Provider' }}
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary fs-6">
                                    {{ formatBytes($service['total_bytes']) }}
                                </div>
                                <small class="text-muted">
                                    {{ number_format($service['percentage'] ?? 0, 1) }}%
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Tidak ada data layanan yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 0.5rem;
}

.hover-highlight {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.hover-highlight:hover {
    background-color: rgba(13, 110, 253, 0.05) !important;
    border-color: rgba(13, 110, 253, 0.2);
    transform: translateX(5px);
}

.service-rank .badge {
    font-size: 0.875rem;
    font-weight: 600;
}

#timeRange {
    border-radius: 0.5rem;
    border-color: #dee2e6;
    transition: all 0.2s ease;
}

#timeRange:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>

<script>
let serviceChart = null;

// Initialize chart
function initServiceChart(data) {
    const ctx = document.getElementById('topServiceChart');
    if (!ctx) return;

    // Destroy existing chart
    if (serviceChart) {
        serviceChart.destroy();
    }

    // Prepare data
    const labels = data.map(item => item.asn || 'Unknown');
    const bytes = data.map(item => item.total_bytes || 0);
    const colors = [
        '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545',
        '#fd7e14', '#ffc107', '#20c997', '#0dcaf0', '#198754'
    ];

    serviceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Traffic Usage',
                data: bytes,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: colors.slice(0, labels.length),
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Traffic: ' + formatBytes(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return formatBytes(value);
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

// Format bytes helper
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Load data based on time range
async function loadServiceData(timeRange) {
    const loadingEl = document.getElementById('chartLoading');
    if (loadingEl) loadingEl.style.display = 'flex';

    try {
        // Replace with your actual API endpoint
        const response = await fetch(`/api/top-services?range=${timeRange}`);
        const data = await response.json();
        
        // Update chart
        if (data.services && data.services.length > 0) {
            initServiceChart(data.services);
            updateServiceList(data.services);
        }
    } catch (error) {
        console.error('Error loading service data:', error);
    } finally {
        if (loadingEl) loadingEl.style.display = 'none';
    }
}

// Update service list
function updateServiceList(services) {
    const listEl = document.getElementById('serviceList');
    if (!listEl || !services.length) return;

    const total = services.reduce((sum, s) => sum + (s.total_bytes || 0), 0);
    
    listEl.innerHTML = services.map((service, index) => {
        const percentage = total > 0 ? ((service.total_bytes / total) * 100).toFixed(1) : 0;
        return `
            <div class="service-item d-flex justify-content-between align-items-center mb-3 p-3 rounded-3 bg-light bg-opacity-50 hover-highlight">
                <div class="d-flex align-items-center flex-grow-1">
                    <div class="service-rank me-3">
                        <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                            ${index + 1}
                        </span>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark mb-1">
                            <i class="bi bi-hdd-network text-primary me-2"></i>
                            ${service.asn || 'Unknown ASN'}
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-building me-1"></i>
                            ${service.name || 'Unknown Provider'}
                        </small>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary fs-6">
                        ${formatBytes(service.total_bytes)}
                    </div>
                    <small class="text-muted">
                        ${percentage}%
                    </small>
                </div>
            </div>
        `;
    }).join('');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize chart with existing data
    const initialData = @json($services ?? []);
    if (initialData.length > 0) {
        initServiceChart(initialData);
    }

    // Time range change handler
    const timeRangeSelect = document.getElementById('timeRange');
    if (timeRangeSelect) {
        timeRangeSelect.addEventListener('change', function() {
            loadServiceData(this.value);
        });
    }
});
</script>
@endsection

