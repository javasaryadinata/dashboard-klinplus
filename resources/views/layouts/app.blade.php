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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css'])
</head>
<body class="font-poppins">
    <div id="app" class="app-container flex min-h-screen">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="font-bold text-xl text-sky-400">KLINPLUS</h1>
            </div>
            
            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <h2 class="tracking-wider sidebar-section-title">DASHBOARD</h2>
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                                <x-lucide-layout-grid class="h-4 w-4 stroke-current stroke-[1.8]" />
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
                                <x-lucide-file-text class="lucide-icon h-4 w-4 stroke-current stroke-[1.8]" />
                                <span>Orders</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                                <x-lucide-calendar-days class="h-4 w-4 stroke-current stroke-[1.8]" />
                                <span>Jadwal</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pembayaran.index') }}" class="{{ request()->routeIs('pembayaran.*') || request('ref') === 'pembayaran' ? 'active' : ''}}">
                                <x-lucide-credit-card class="h-4 w-4 stroke-current stroke-[2]" />
                                <span>Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
                                <x-lucide-history class="h-4 w-4 stroke-current stroke-[2]" />
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
                                <x-lucide-user class="h-4 w-4 stroke-current stroke-[2]" />
                                <span>Pelanggan</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('petugas.index') }}" class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                                <x-lucide-users class="h-4 w-4 stroke-current stroke-[2]" />
                                <span>Petugas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                                <x-lucide-package-2 class="h-4 w-4 stroke-current stroke-[1.8]" />
                                <span>Layanan</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <main class="main-content flex-1 flex flex-col min-h-screen bg-slate-100">
            <div class="bg-white rounded-br-xl px-4 py-3 shadow-sm border border-gray-100 overflow-hidden">
                @yield('title-content')
            </div>

            <section class="flex-1 p-3 lg:p-2 flex flex-col gap-2 w-full">
                <div class="bg-white rounded-xl px-3 py-3 shadow-sm border border-gray-100 overflow-hidden">
                    @yield('content')
                </div>    
            </section>
            
            <footer class="mt-auto px-4 py-3 2xl:px-4 2xl:py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center text-xs 2xl:text-sm">
                <div class="text-gray-600">
                    Copyright © 2025 • Design By PT. Sinergi Cakra Inovasi
                </div>
                <div class="text-gray-600">V1.0</div>
            </footer>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    @stack('scripts')
</body>
</html>