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

@section('country')
<div class="row g-3">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-0 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0">TOP Services (AS)</h5>
                <select id="timeRange" class="form-select form-select-sm w-auto">
                    <option value="h">1 Jam</option>
                    <option value="d">1 Hari</option>
                    <option value="w">1 Minggu</option>
                    <option value="m">1 Bulan</option>
                    <option value="y">1 Tahun</option>
                </select>
            </div>

            <div class="card-body">
                <div style="position: relative;">
                    <div id="chartLoading" class="loading-overlay">
                        <div class="spinner"></div>
                    </div>
                    <canvas id="topServiceChart" height="120"></canvas>
                </div>
                <hr>
                <div id="serviceList">
                    @include('home.partials.service-list', ['services' => $services])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .loading-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner {
        width: 30px;
        height: 30px;
        border: 3px solid #e2e8f0;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Enhanced service list */
    .service-item-enhanced {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }

    .service-item-enhanced:last-child {
        border-bottom: none;
    }

    .service-item-enhanced:hover {
        background: #f9f9f9;
        padding-left: 8px;
        padding-right: 8px;
        margin-left: -8px;
        margin-right: -8px;
        border-radius: 8px;
    }

    .service-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .service-rank {
        min-width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.85em;
        color: white;
        flex-shrink: 0;
    }

    .rank-1 {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #333;
    }

    .rank-2 {
        background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
    }

    .rank-3 {
        background: linear-gradient(135deg, #cd7f32 0%, #e8a76a 100%);
    }

    .rank-other {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .service-info-text {
        min-width: 0;
    }

    .service-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 0.95em;
        margin-bottom: 2px;
    }

    .service-meta {
        font-size: 0.85em;
        color: #a0aec0;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .service-usage {
        text-align: right;
        flex-shrink: 0;
    }

    .usage-value {
        font-weight: 700;
        color: #667eea;
        font-size: 1em;
    }

    .usage-label {
        font-size: 0.8em;
        color: #a0aec0;
    }

    @media (max-width: 768px) {
        .service-item-enhanced {
            flex-wrap: wrap;
        }

        .service-meta {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartInstance;
let services = @json($services);

// Utility function untuk format bytes
function formatBytes(bytes) {
    if (!bytes || bytes === 0) return '0 B';
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    let i = 0;
    while (bytes >= 1024 && i < units.length - 1) {
        bytes /= 1024;
        i++;
    }
    return bytes.toFixed(2) + ' ' + units[i];
}

// Render chart dari service data
function renderChart(data) {
    if (!data || data.length === 0) {
        console.warn('No data to render');
        return;
    }

    const ctx = document.getElementById('topServiceChart').getContext('2d');
    if (chartInstance) chartInstance.destroy();

    // Extract data dari service array
    const labels = data.map(s => s.asn || 'Unknown');
    const chartData = data.map(s => s.total_bytes || 0);
    
    const colors = [
        'rgba(102, 126, 234, 0.8)',
        'rgba(118, 75, 162, 0.8)',
        'rgba(244, 63, 94, 0.8)',
        'rgba(255, 159, 64, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(201, 203, 207, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 99, 132, 0.8)'
    ];

    const backgroundColors = colors.slice(0, chartData.length);

    try {
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penggunaan Kuota',
                    data: chartData,
                    backgroundColor: backgroundColors,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                return formatBytes(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatBytes(value);
                            }
                        },
                        grid: {
                            color: 'rgba(200, 200, 200, 0.1)'
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Chart rendering error:', error);
    }
}

// Update data berdasarkan time range
function updateData(range) {
    const loading = document.getElementById('chartLoading');
    if (loading) loading.classList.add('active');

    console.log('Fetching data for range:', range);
    
    fetch(`{{ route('home.top-services') }}?range=${range}`)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(res => {
            console.log('Response:', res);
            
            if (res.success && Array.isArray(res.data)) {
                services = res.data;
                renderChart(services);
                updateServiceList(services);
            } else {
                console.error('Invalid response format:', res);
                updateServiceList([]);
            }
        })
        .catch(err => {
            console.error('Error fetching data:', err);
            updateServiceList([]);
        })
        .finally(() => {
            if (loading) loading.classList.remove('active');
        });
}

// Update service list HTML
function updateServiceList(data) {
    let html = '';
    
    if (data && data.length > 0) {
        data.forEach((s, idx) => {
            const rankClass = idx <= 2 ? `rank-${idx + 1}` : 'rank-other';
            html += `
                <div class="service-item-enhanced">
                    <div class="service-left">
                        <div class="service-rank ${rankClass}">
                            ${idx + 1}
                        </div>
                        <div class="service-info-text">
                            <div class="service-name">${s.asn || 'Unknown ASN'}</div>
                            <div class="service-meta">
                                <span>${s.name || 'Unknown'}</span>
                                <span>🌍 ${s.country || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="service-usage">
                        <div class="usage-value">${formatBytes(s.total_bytes)}</div>
                    </div>
                </div>
            `;
        });
    } else {
        html = '<p class="text-muted text-center py-3">Tidak ada data layanan yang tersedia.</p>';
    }
    
    document.getElementById('serviceList').innerHTML = html;
}

// Event listener
document.getElementById('timeRange').addEventListener('change', function(e) {
    updateData(e.target.value);
});

// Initial render
document.addEventListener('DOMContentLoaded', function() {
    if (services && services.length > 0) {
        renderChart(services);
        updateServiceList(services);
    }
});
</script>
@endpush