<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinplus | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body>
    <div id="app" class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">KLINPLUS</h1>
            </div>
            
            <nav class="sidebar-nav">
                <!-- Dashboard Section -->
                <div class="sidebar-section">
                    <h2 class="sidebar-section-title">DASHBOARD</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ url('/')  }}" class="{{ request()->is('/') ? 'active' : '' }}">
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- LAPORAN Section -->
                <div class="sidebar-section">
                    <h2 class="sidebar-section-title">LAPORAN</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>Orders</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.index') ? 'active' : '' }}">
                                <i class="bi bi-calendar-week-fill"></i>
                                <span>Jadwal</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pembayaran.index') }}" class="{{ request()->routeIs('pembayaran.index') ? 'active' : '' }}">
                                <i class="bi bi-credit-card-2-back-fill"></i>
                                <span>Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.index') ? 'active' : '' }}">
                                <i class="bi bi-hourglass-bottom"></i>
                                <span>Riwayat</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- MASTER DATA Section -->
                <div class="sidebar-section">
                    <h2 class="sidebar-section-title">MASTER DATA</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pelanggan.index') }}" class="{{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Pelanggan</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('petugas.index') }}" class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                                <i class="bi bi-person-lines-fill"></i>
                                <span>Petugas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                                <i class="bi bi-archive-fill"></i>
                                <span>Layanan</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Logout -->
                <div class="sidebar-logout">
                    <a href="#" class="logout-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4 20V4h8.02v1H5v14h7.02v1zm12.462-4.461l-.702-.72l2.319-2.319H9.192v-1h8.887l-2.32-2.32l.702-.718L20 12z"/></svg>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            {{-- <!-- Navbar -->
            <header class="navbar">
                <div class="navbar-profile"></div>
            </header> --}}
            
            <!-- Content Section -->
            <section class="content-section">
                <div class="welcome-message">
                    @yield('title-content')
                </div>
                
                <div class="content-container">
                    @yield('content')
                </div>
            </section>
            
            <!-- Footer -->
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright © 2025 • Design By PT. Sinergi Cakra Inovasi
                </div>
                <div class="footer-right">V1.0</div>
            </footer>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
