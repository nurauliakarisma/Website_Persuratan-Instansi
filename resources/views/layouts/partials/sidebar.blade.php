@php
    $role = auth()->user()->tipe ?? request()->user()->tipe ?? '';
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme shadow-sm">
    <div class="app-brand justify-content-center pt-3 pb-2 px-3">
        <a href="{{ route('dashboard') }}" class="app-brand-link d-flex flex-column align-items-center text-decoration-none">
            <img src="{{ asset('images/avatars/logo.png') }}" width="62" height="58" alt="Logo DPRD Provinsi Jawa Timur" class="mb-1" style="object-fit: contain;">
            <span class="app-brand-text menu-text fw-bold text-center fs-6 text-dark" style="letter-spacing: -0.3px;">Pusat Persuratan</span>
            <small class="text-muted text-center fw-semibold" style="font-size: 10.5px;">DPRD PROV. JAWA TIMUR</small>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-3">
        @if ($role == 'Staff')
            <!-- Dashboard Staff -->
            <li class="menu-item {{ request()->routeIs('menu.index') ? 'active' : '' }}">
                <a href="{{ route('menu.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
        @else
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>

            @if (in_array($role, ['Super Admin']))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Manajemen Pengguna</span>
                </li>

                <!-- Admin -->
                <li class="menu-item {{ request()->routeIs('user.admin') ? 'active' : '' }}">
                    <a href="{{ route('user.admin') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                        <div data-i18n="Admin">Admin</div>
                    </a>
                </li>

                <!-- Pendataan Staff -->
                <li class="menu-item {{ request()->routeIs('user.staff') ? 'active' : '' }}">
                    <a href="{{ route('user.staff') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-id-card"></i>
                        <div data-i18n="Pendataan Staff">Pendataan Staff</div>
                    </a>
                </li>

                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Pengaturan Sistem</span>
                </li>

                {{-- Master Data --}}
                <li class="menu-item {{ request()->is('master*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layer"></i>
                        <div data-i18n="Master Data">Master Data</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('master.sub-kegiatan.index') ? 'active' : '' }}">
                            <a href="{{ route('master.sub-kegiatan.index') }}" class="menu-link">
                                <i class="bx bx-list-check me-2"></i>
                                <div data-i18n="Sub Kegiatan">Sub Kegiatan</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('master.rincian-belanja.index') ? 'active' : '' }}">
                            <a href="{{ route('master.rincian-belanja.index') }}" class="menu-link">
                                <i class="bx bx-receipt me-2"></i>
                                <div data-i18n="Rincian Belanja">Rincian Belanja</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('master.index-kegiatan.index') ? 'active' : '' }}">
                            <a href="{{ route('master.index-kegiatan.index', ['bagian' => 'A']) }}" class="menu-link">
                                <i class="bx bx-bookmark-alt me-2"></i>
                                <div data-i18n="Index Kegiatan">Index Kegiatan</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Layanan Surat</span>
            </li>

            <!-- Bagian Dokinfo -->
            @if (in_array($role, ['Super Admin', 'Admin A']))
                @php
                    $menuA = in_array(request()->segment(2), ['BagianDokinfo', 'A']) || request()->is('media*');
                @endphp
                <li class="menu-item {{ $menuA ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-news"></i>
                        <div data-i18n="Data Surat Dokinfo">Data Surat Dokinfo</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item {{ (request()->is('npd/BagianDokinfo*') || request()->is('npd/A*')) ? 'active' : '' }}">
                            <a href="{{ route('npd.index', ['bagian' => 'BagianDokinfo']) }}" class="menu-link">
                                <i class="bx bx-credit-card-front me-2"></i>
                                <div data-i18n="NPD">NPD</div>
                            </a>
                        </li>
                        <li class="menu-item {{ (request()->is('nodin/BagianDokinfo*') || request()->is('nodin/A*')) && !request()->routeIs('nodin.rekap') ? 'active' : '' }}">
                            <a href="{{ route('nodin.index', ['bagian' => 'BagianDokinfo']) }}" class="menu-link">
                                <i class="bx bx-file me-2"></i>
                                <div data-i18n="NODIN">NODIN</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('media*') ? 'active' : '' }}">
                            <a href="{{ route('media.index') }}" class="menu-link">
                                <i class="bx bx-broadcast me-2"></i>
                                <div data-i18n="Media">Media</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('nodin.rekap') && in_array(request()->segment(2), ['BagianDokinfo', 'A']) ? 'active' : '' }}">
                            <a href="{{ route('nodin.rekap', ['bagian' => 'BagianDokinfo']) }}" class="menu-link">
                                <i class="bx bx-calendar-check me-2"></i>
                                <div data-i18n="Rekap NODIN">Rekap NODIN</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Bagian FPP -->
            @if (in_array($role, ['Super Admin', 'Admin B']))
                <li class="menu-item {{ in_array(request()->segment(2), ['BagianFPP', 'B']) ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-briefcase-alt-2"></i>
                        <div data-i18n="Data Surat FPP">Data Surat FPP</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item {{ (request()->is('npd/BagianFPP*') || request()->is('npd/B*')) ? 'active' : '' }}">
                            <a href="{{ route('npd.index', ['bagian' => 'BagianFPP']) }}" class="menu-link">
                                <i class="bx bx-credit-card-front me-2"></i>
                                <div data-i18n="NPD">NPD</div>
                            </a>
                        </li>
                        <li class="menu-item {{ (request()->is('nodin/BagianFPP*') || request()->is('nodin/B*')) && !request()->routeIs('nodin.rekap') ? 'active' : '' }}">
                            <a href="{{ route('nodin.index', ['bagian' => 'BagianFPP']) }}" class="menu-link">
                                <i class="bx bx-file me-2"></i>
                                <div data-i18n="NODIN">NODIN</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('nodin.rekap') && in_array(request()->segment(2), ['BagianFPP', 'B']) ? 'active' : '' }}">
                            <a href="{{ route('nodin.rekap', ['bagian' => 'BagianFPP']) }}" class="menu-link">
                                <i class="bx bx-calendar-check me-2"></i>
                                <div data-i18n="Rekap NODIN">Rekap NODIN</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif
    </ul>
</aside>
