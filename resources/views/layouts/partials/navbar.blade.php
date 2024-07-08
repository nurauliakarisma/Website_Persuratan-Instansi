<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    @if ($hasMenu)
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>
        <div class="layout-menu-toggle-xl navbar-nav align-items-xl-center me-3 me-xl-0 d-none d-xl-block">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>
    @endif

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Page Title -->
        <h1 class="h3 m-0">@yield('page-title', $title)</h1>
        <!-- /Page Title -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar">
                        @php
                            $avatar = request()->useR()->photo
                                ? 'storage/' . request()->user()->photo
                                : 'images/avatars/1.png';
                        @endphp
                        <img src="{{ asset($avatar) }}" alt class="w-px-40 h-px-40 rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <span class="fw-semibold d-block">{{ Auth::user()->nama ?? 'Nama' }}</span>
                            <small class="text-muted">{{ Auth::user()->tipe ?? 'Role' }}</small>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('change-password') }}">
                            Ubah Password
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form action="{{ route('logout.action') }}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
