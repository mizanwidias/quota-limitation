<div class="stats-container">
    <!-- User Profile Card -->
    <div class="card stat-card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-wrapper position-relative">
                    <img src="{{ asset('images/faces/1.jpg') }}" alt="{{ $user->cust_name ?? 'User' }}"
                        class="rounded-circle" width="60" height="60">
                    <div class="status-badge"></div>
                </div>
                <div class="flex-grow-1">
                    <div class="stat-label">Profil Pengguna</div>
                    <h6 class="stat-value mb-1">
                        {{ Auth::user()->cust_name }}
                    </h6>
                    <small class="stat-meta">
                        <i class="bi bi-person-badge"></i> {{ $user['cust_id'] }}<br>
                        <i class="bi bi-telephone"></i> {{ $user['no_hp'] }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Uptime Card -->
    <div class="card stat-card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: var(--primary-gradient); color: white;">
                    <i class="bi bi-gear-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="stat-label">Uptime Perangkat</div>
                    <h6 class="stat-value">{{ $uptime }}</h6>
                    <small class="stat-meta">
                        <i class="bi bi-check-circle text-success"></i>
                        <span class="text-success fw-semibold">Stabil</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Duration Card -->
    <div class="card stat-card shadow-sm border-0">
        <div class="card-body">
            <div class="stat-label">Masa Aktif Langganan</div>
            <h6 class="stat-value mb-2">18 Hari</h6>
            <div class="progress-custom">
                <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
            <div class="progress-label">
                <span>Progres</span>
                <span class="fw-bold">60% dari 30 hari</span>
            </div>
        </div>
    </div>

    <!-- Total Usage Card -->
    <div class="card stat-card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="stat-label">📊 Total Pemakaian Kuota Bulanan</div>
            <h6 class="stat-value mb-2">{{ $usage['total'] }}</h6>
            <div class="progress-custom">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persentase ?? '0' }}%;"
                    aria-valuenow="{{ $persentase ?? '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="progress-label">
                <span>Kapasitas</span>
                <span class="fw-bold text-danger">{{ number_format($persentase ?? 0, 1) }}% dari {{ $limit ?? 'N/A' }}
                    GB</span>
            </div>
        </div>
    </div>
</div>
