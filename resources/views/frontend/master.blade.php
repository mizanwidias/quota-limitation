<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.svg') }}" type="image/x-icon">

    <style>
        /* Enhanced Dashboard Styling */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            --info-gradient: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .page-heading h3 {
            font-weight: 700;
            font-size: 1.8em;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Stat Card Enhancement */
        .stat-card {
            border-radius: 16px;
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.15);
            border-color: rgba(102, 126, 234, 0.2);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-label {
            font-size: 0.85em;
            font-weight: 600;
            color: #718096;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.6em;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .stat-meta {
            font-size: 0.9em;
            color: #a0aec0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Progress Enhancement */
        .progress-custom {
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 8px 0;
        }

        .progress-custom .progress-bar {
            border-radius: 10px;
            background: var(--primary-gradient);
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from { width: 0 !important; }
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.85em;
            color: #718096;
            margin-top: 6px;
        }

        /* Avatar Enhancement */
        .avatar-wrapper {
            position: relative;
        }

        .avatar-wrapper img {
            border: 3px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover .avatar-wrapper img {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .status-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 16px;
            height: 16px;
            background: #48bb78;
            border: 2px solid white;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(72, 187, 120, 0); }
            100% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0); }
        }

        /* Card Container */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Section Divider */
        .section-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 40px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 1.4em;
            }

            .page-heading h3 {
                font-size: 1.5em;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="{{ route('home') }}"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo"></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                
                        <li class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                
                        <li class="sidebar-item {{ request()->routeIs('kuota*') ? 'active' : '' }}">
                            <a href="{{ route('kuota') }}" class="sidebar-link">
                                <i class="bi bi-wifi"></i>
                                <span>Paket Kuota Pilihan</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>

        <!-- Main Content -->
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Profile</h3>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <!-- Stats Cards -->
                        <div class="stats-container">
                            <!-- User Profile Card -->
                            <div class="card stat-card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-wrapper position-relative">
                                            <img src="{{ asset('images/faces/1.jpg') }}" alt="John Duck" class="rounded-circle" width="60" height="60">
                                            <div class="status-badge"></div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="stat-label">Profil Pengguna</div>
                                            <h6 class="stat-value mb-1">John Duck</h6>
                                            <small class="stat-meta">
                                                <i class="bi bi-telephone"></i> 08080808080
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
                                            <h6 class="stat-value">1D 1H 23M</h6>
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
                                        <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress-label">
                                        <span>Progres</span>
                                        <span class="fw-bold">60% dari 30 hari</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Usage Card -->
                            <div class="card stat-card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="stat-label">📊 Total Pemakaian Kuota</div>
                                    <h6 class="stat-value mb-2">699 GB</h6>
                                    <div class="progress-custom">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 87.4%;" aria-valuenow="87.4" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress-label">
                                        <span>Kapasitas</span>
                                        <span class="fw-bold text-danger">87.4% dari 800 GB</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <!-- Charts Section -->
                        @yield('chart')
                    </div>
                </section>

                <!-- Country/Services Section -->
                <section class="row mt-4">
                    @yield('serviceCharts')
                </section>

                <!-- Additional Content -->
                @yield('content')
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2025 &copy; Hyperlink</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted by <a href="http://hyperlink.my.id">Hyperlink</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>