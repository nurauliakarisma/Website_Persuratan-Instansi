@php
    $role = request()->user()->tipe ?? '';
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
<div class="margin-top">
            <img src="http://127.0.0.1:8000/images/image.png" width="70" height="30">
    </div>
    <div class="app-brand justify-content-center">
            <img src="http://127.0.0.1:8000/images/avatars/logo.png" width="70" height="65">
    </div>
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Pusat Persuratan </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if ($role == 'Staff')
            <!-- Dashboard -->
            <li class="menu-item">
                <a href="{{ route('menu.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>
        @else
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Dashboard</div>
                </a>
            </li>
            @if (in_array($role, ['Super Admin']))
                <li class="menu-item {{ request()->routeIs('user.admin') ? 'active' : '' }}">
                    <a href="{{ route('user.admin') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user-plus"></i>
                        <div data-i18n="Analytics">Admin</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('user.staff') ? 'active' : '' }}">
                    <a href="{{ route('user.staff') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user-pin"></i>
                        <div data-i18n="Analytics">Pendataan Staff</div>
                    </a>
                </li>
                {{-- Master data --}}
                <li class="menu-item {{ request()->is('master*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-data"></i>
                        <div data-i18n="Layouts">Master Data</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->routeIs('master.sub-kegiatan.index') ? 'active' : '' }}">
                            <a href="{{ route('master.sub-kegiatan.index') }}" class="menu-link">
                                <div data-i18n="Without menu">Sub Kegiatan</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('master.rincian-belanja.index') ? 'active' : '' }}">
                            <a href="{{ route('master.rincian-belanja.index') }}" class="menu-link">
                                <div data-i18n="Without navbar">Rincian Belanja</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('master.index-kegiatan.index') ? 'active' : '' }}">
                            <a href="{{ route('master.index-kegiatan.index', ['bagian' => 'A']) }}" class="menu-link">
                                <div data-i18n="Without navbar">Index Kegiatan</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Bagian A -->
            @if (in_array($role, ['Super Admin', 'Admin A']))
                @php
                    $menuA = request()->segment(2) == 'A' || request()->is('media*');
                @endphp
                <li class="menu-item {{ $menuA ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-data"></i>
                        <div data-i18n="Layouts">Data Surat Dokinfo</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->is('npd/A*') ? 'active' : '' }}">
                            <a href="{{ route('npd.index', ['bagian' => 'A']) }}" class="menu-link">
                                <div data-i18n="Without menu">NPD</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->is('nodin/A*') && !request()->routeIs('nodin.rekap') ? 'active' : '' }}">
                            <a href="{{ route('nodin.index', ['bagian' => 'A']) }}" class="menu-link">
                                <div data-i18n="Without navbar">NODIN</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->is('media*') ? 'active' : '' }}">
                            <a href="{{ route('media.index') }}" class="menu-link">
                                <div data-i18n="Container">Media</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('nodin.rekap') ? 'active' : '' }}">
                            <a href="{{ route('nodin.rekap', ['bagian' => 'A']) }}" class="menu-link">
                                <div data-i18n="Fluid">Rekap NODIN</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Bagian B -->
            @if (in_array($role, ['Super Admin', 'Admin B']))
                <li class="menu-item {{ request()->segment(2) == 'B' ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-data"></i>
                        <div data-i18n="Layouts">Data Surat FPP</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item {{ request()->is('npd/B*') ? 'active' : '' }}">
                            <a href="{{ route('npd.index', ['bagian' => 'B']) }}" class="menu-link">
                                <div data-i18n="Without menu">NPD</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ request()->is('nodin/B*') && !request()->routeIs('nodin.rekap') ? 'active' : '' }}">
                            <a href="{{ route('nodin.index', ['bagian' => 'B']) }}" class="menu-link">
                                <div data-i18n="Without navbar">NODIN</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('nodin.rekap') ? 'active' : '' }}">
                            <a href="{{ route('nodin.rekap', ['bagian' => 'B']) }}" class="menu-link">
                                <div data-i18n="Fluid">Rekap NODIN</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif
    </ul>
</aside>
