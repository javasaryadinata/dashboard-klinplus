<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinplus | Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css'])
</head>
<body class="font-poppins">
    <div id="app" class="app-container flex min-h-screen">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title !text-[#2ac6ea]">KLINPLUS</h1>
            </div>
            
            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <h2 class="tracking-wider sidebar-section-title">DASHBOARD</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-layout-grid-icon lucide-layout-grid"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h2 class="tracking-wider sidebar-section-title">LAPORAN</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') && request('ref') !== 'pembayaran' ? 'active' : ''}}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-file-text-icon lucide-file-text"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                <span>Orders</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-calendar-days-icon lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                                <span>Jadwal</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pembayaran.index') }}" class="{{ request()->routeIs('pembayaran.*') || request('ref') === 'pembayaran' ? 'active' : ''}}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                <span>Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-history-icon lucide-history"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                                <span>Riwayat</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h2 class="tracking-wider sidebar-section-title">MASTER DATA</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pelanggan.index') }}" class="{{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>Pelanggan</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('petugas.index') }}" class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg> 
                                <span>Petugas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-5 2xl:w-6 lucide lucide-package2-icon lucide-package-2"><path d="M12 3v6"/><path d="M16.76 3a2 2 0 0 1 1.8 1.1l2.23 4.479a2 2 0 0 1 .21.891V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.472a2 2 0 0 1 .211-.894L5.45 4.1A2 2 0 0 1 7.24 3z"/><path d="M3.054 9.013h17.893"/></svg>
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
            <section class="flex-1 p-3 lg:p-3 flex flex-col gap-3 w-full">
                <div class="bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-100 overflow-hidden">
                    @yield('title-content')
                </div>
                
                <div class="bg-white rounded-xl px-4 py-3 shadow-sm border border-gray-100 overflow-hidden">
                    @yield('content')
                </div>    
            </section>
            
            <footer class="mt-auto px-4 py-3 2xl:px-4 2xl:py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center text-xs 2xl:text-sm">
                <div class="text-grey">
                    Copyright © 2025 • Design By PT. Sinergi Cakra Inovasi
                </div>
                <div class="text-cyan 2xl:text-cyan">V1.0</div>
            </footer>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
</body>
</html>