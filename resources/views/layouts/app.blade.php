<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinplus | Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#f4f6f9] text-gray-700 font-poppins">
    <div id="app" class="app-container flex min-h-screen">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">KLINPLUS</h1>
            </div>
            
            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <h2 class="sidebar-section-title">DASHBOARD</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                                <i class="bi bi-grid-1x2-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h2 class="sidebar-section-title">LAPORAN</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>Orders</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-week-fill"></i>
                                <span>Jadwal</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pembayaran.index') }}" class="{{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                                <i class="bi bi-credit-card-2-back-fill"></i>
                                <span>Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
                                <i class="bi bi-hourglass-bottom"></i>
                                <span>Riwayat</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
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
                
                <div class="sidebar-logout">
                    <a href="#" class="logout-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4 20V4h8.02v1H5v14h7.02v1zm12.462-4.461l-.702-.72l2.319-2.319H9.192v-1h8.887l-2.32-2.32l.702-.718L20 12z"/>
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <main class="main-content flex-1 flex flex-col min-h-screen">
            
            <section class="flex-1 p-6 md:p-8 flex flex-col gap-6 w-full">
                
                <div class="flex items-center w-full">
                    @yield('title-content')
                </div>
                
                <div class="w-full">
                    @yield('content')
                </div>
                
            </section>
            
            <footer class="mt-auto px-8 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
                <div class="mb-2 sm:mb-0">
                    Copyright © 2025 • Design By PT. Sinergi Cakra Inovasi
                </div>
                <div class="font-medium text-[#2ac6ea]">V1.0</div>
            </footer>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
</body>
</html>