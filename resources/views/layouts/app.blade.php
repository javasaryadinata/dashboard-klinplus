<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLINPLUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" 
                                    d="M17.66 11.2c-.23-.3-.51-.56-.77-.82c-.67-.6-1.43-1.03-2.07-1.66C13.33 7.26 13 4.85 13.95 3c-.95.23-1.78.75-2.49 1.32c-2.59 2.08-3.61 5.75-2.39 8.9c.04.1.08.2.08.33c0 .22-.15.42-.35.5c-.23.1-.47.04-.66-.12a.6.6 0 0 1-.14-.17c-1.13-1.43-1.31-3.48-.55-5.12C5.78 10 4.87 12.3 5 14.47c.06.5.12 1 .29 1.5c.14.6.41 1.2.71 1.73c1.08 1.73 2.95 2.97 4.96 3.22c2.14.27 4.43-.12 6.07-1.6c1.83-1.66 2.47-4.32 1.53-6.6l-.13-.26c-.21-.46-.77-1.26-.77-1.26m-3.16 6.3c-.28.24-.74.5-1.1.6c-1.12.4-2.24-.16-2.9-.82c1.19-.28 1.9-1.16 2.11-2.05c.17-.8-.15-1.46-.28-2.23c-.12-.74-.1-1.37.17-2.06c.19.38.39.76.63 1.06c.77 1 1.98 1.44 2.24 2.8c.04.14.06.28.06.43c.03.82-.33 1.72-.93 2.27"/></svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect width="14" height="17" x="5" y="4" rx="2" />
                                        <path stroke-linecap="round" d="M9 9h6m-6 4h6m-6 4h4" />
                                    </g>
                                </svg>
                                <span>Orders</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('jadwal') }}" class="{{ request()->routeIs('jadwal') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect width="14" height="17" x="5" y="4" rx="2" />
                                        <path stroke-linecap="round" d="M9 9h6m-6 4h6m-6 4h4" />
                                    </g>
                                </svg>
                                <span>Jadwal</span>    
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('pembayaran') }}" class="{{ request()->routeIs('order-selesai') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect width="14" height="17" x="5" y="4" rx="2" />
                                        <path stroke-linecap="round" d="M9 9h6m-6 4h6m-6 4h4" />
                                    </g>
                                </svg>
                                <span>Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('riwayat') }}" class="{{ request()->routeIs('layanan-selesai') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect width="14" height="17" x="5" y="4" rx="2" />
                                        <path stroke-linecap="round" d="M9 9h6m-6 4h6m-6 4h4" />
                                    </g>
                                </svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 45 45">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M24 20a7 7 0 1 0 0-14a7 7 0 0 0 0 14M6 40.8V42h36v-1.2c0-4.48 0-6.72-.872-8.432a8 8 0 0 0-3.496-3.496C35.92 28 33.68 28 29.2 28H18.8c-4.48 0-6.72 0-8.432.872a8 8 0 0 0-3.496 3.496C6 34.08 6 36.32 6 40.8"/></svg>
                                <span>Pelanggan</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('petugas.index') }}" class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="m9.108 11.758l2.284-2.29l-2.053-2.06l-.805.804q-.14.14-.34.153t-.367-.153q-.166-.166-.166-.357t.166-.357l.798-.798l-1.952-1.952l-2.29 2.29zm7.834 7.84l2.29-2.29l-1.951-1.952l-.804.798q-.146.146-.344.156t-.364-.156t-.165-.354t.165-.354l.798-.804l-2.04-2.034l-2.285 2.284zM17.273 5l1.733 1.733zM4.808 20q-.348 0-.578-.23T4 19.192v-2.017q0-.161.056-.301t.186-.27l4.152-4.152l-4.748-4.767q-.271-.271-.271-.646t.271-.647l2.381-2.38q.271-.271.646-.269t.646.274l4.793 4.767l4.467-4.473q.165-.165.348-.238t.39-.073t.39.073t.349.238l1.632 1.697q.166.165.23.348t.063.39t-.064.378t-.228.336l-4.43 4.454l4.73 4.767q.27.271.27.646t-.27.646l-2.381 2.38q-.271.272-.646.272t-.646-.271l-4.768-4.748l-4.152 4.152q-.13.13-.27.186T6.825 20zM5 19h1.727l9.82-9.814l-1.734-1.732L5 17.273zM15.692 8.314l-.878-.86l1.732 1.733z"/></svg>
                                <span>Petugas</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('layanan.index') }}" class="{{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M16.008 19q-.356 0-.586-.232q-.23-.233-.23-.576v-4.184q0-.356.232-.586q.233-.23.576-.23h4.185q.356 0 .585.233T21 14v4.185q0 .356-.232.585t-.576.23zM12 10.808q-.343 0-.575-.232T11.192 10V5.815q0-.355.233-.585T12 5h8.192q.344 0 .576.232t.232.576v4.185q0 .355-.232.585q-.233.23-.576.23zM3.808 19q-.343 0-.576-.232T3 18.192v-4.184q0-.356.232-.586t.576-.23H12q.343 0 .576.232t.232.576v4.185q0 .356-.232.585T12 19zm.007-8.192q-.355 0-.585-.232T3 10V5.815q0-.355.232-.585T3.808 5h4.185q.355 0 .585.232t.23.576v4.185q0 .355-.232.585q-.233.23-.576.23z"/></svg>
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
            <!-- Navbar -->
            <header class="navbar">
                <div class="navbar-profile"></div>
            </header>
            
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
                    Copyright © 2025 • Design By PT.Sinergi Cakra Inovasi
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
