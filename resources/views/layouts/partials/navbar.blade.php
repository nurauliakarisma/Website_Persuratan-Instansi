@php
    $currentUser = auth()->user() ?? request()->user();
    $avatar = ($currentUser && !empty($currentUser->photo))
        ? 'storage/' . $currentUser->photo
        : 'images/avatars/1.png';
    $roleName = $currentUser->tipe ?? 'User';
    if ($roleName === 'Admin A') $roleName = 'Admin Dokinfo';
    elseif ($roleName === 'Admin B') $roleName = 'Admin FPP';

    // Collect latest notifications
    $notifications = collect();
    if ($currentUser) {
        $role = $currentUser->tipe ?? '';
        $nama = $currentUser->nama ?? '';

        // 1. NODIN
        $nodinQuery = \App\Models\PengajuanNODIN::latest('updated_at');
        if ($role === 'Staff') {
            $nodinQuery->where('nama_penginput', $nama);
        } elseif ($role === 'Admin A') {
            $nodinQuery->whereIn('bagian', ['A', 'BagianDokinfo']);
        } elseif ($role === 'Admin B') {
            $nodinQuery->whereIn('bagian', ['B', 'BagianFPP']);
        }
        foreach ($nodinQuery->take(5)->get() as $n) {
            $bagianSlug = in_array($n->bagian, ['A', 'BagianDokinfo']) ? 'BagianDokinfo' : 'BagianFPP';
            $notifications->push([
                'id' => 'nodin-' . $n->id . '-' . ($n->updated_at ? $n->updated_at->timestamp : '0'),
                'type' => 'NODIN',
                'title' => 'NODIN: ' . ($n->nomor ?? 'Draft'),
                'desc' => $n->subject ?? $n->perihal ?? 'Pengajuan Nota Dinas',
                'status' => $n->status,
                'time' => $n->updated_at,
                'user' => $n->nama_penginput,
                'url' => route('nodin.detail', ['bagian' => $bagianSlug]),
            ]);
        }

        // 2. NPD
        $npdQuery = \App\Models\PengajuanNPD::latest('updated_at');
        if ($role === 'Staff') {
            $npdQuery->where('nama_penginput', $nama);
        } elseif ($role === 'Admin A') {
            $npdQuery->whereIn('bagian', ['A', 'BagianDokinfo']);
        } elseif ($role === 'Admin B') {
            $npdQuery->whereIn('bagian', ['B', 'BagianFPP']);
        }
        foreach ($npdQuery->take(5)->get() as $npd) {
            $bagianSlug = in_array($npd->bagian, ['A', 'BagianDokinfo']) ? 'BagianDokinfo' : 'BagianFPP';
            $notifications->push([
                'id' => 'npd-' . $npd->id . '-' . ($npd->updated_at ? $npd->updated_at->timestamp : '0'),
                'type' => 'NPD',
                'title' => 'NPD: ' . ($npd->nomor ?? 'Draft'),
                'desc' => $npd->uraian_kegiatan ?? 'Pengajuan NPD',
                'status' => $npd->status,
                'time' => $npd->updated_at,
                'user' => $npd->nama_penginput,
                'url' => route('npd.detail', ['bagian' => $bagianSlug]),
            ]);
        }

        // 3. Media
        $mediaQuery = \App\Models\PengajuanPublikasi::latest('updated_at');
        if ($role === 'Staff') {
            $mediaQuery->where('nama_penginput', $nama);
        }
        foreach ($mediaQuery->take(5)->get() as $m) {
            $notifications->push([
                'id' => 'media-' . $m->id . '-' . ($m->updated_at ? $m->updated_at->timestamp : '0'),
                'type' => 'Media',
                'title' => 'Publikasi: ' . ($m->media->nama ?? 'Media'),
                'desc' => $m->judul ?? 'Pengajuan Publikasi',
                'status' => $m->status,
                'time' => $m->updated_at,
                'user' => $m->nama_penginput,
                'url' => route('media.show'),
            ]);
        }

        $notifications = $notifications->sortByDesc('time')->take(8)->values();
    }
@endphp

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    @if ($hasMenu)
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" title="Buka Sidebar">
                <i class="bx bx-menu bx-sm text-primary fs-3"></i>
            </a>
        </div>
        <div class="layout-menu-toggle-xl navbar-nav align-items-xl-center me-3 me-xl-0 d-none d-xl-block">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" title="Buka / Tutup Sidebar">
                <i class="bx bx-menu bx-sm text-primary fs-3"></i>
            </a>
        </div>
    @else
        <div class="navbar-nav align-items-center me-3">
            <a href="{{ ($currentUser->tipe ?? '') === 'Staff' ? route('menu.index') : route('dashboard') }}" class="app-brand-link d-flex align-items-center text-decoration-none py-1 pe-2" title="Kembali ke Beranda">
                <img src="{{ asset('images/avatars/logo.png') }}" width="34" height="32" alt="Logo DPRD" class="me-2" style="object-fit: contain;">
                <div class="d-none d-sm-flex flex-column text-nowrap" style="white-space: nowrap; line-height: 1.15;">
                    <span class="fw-bold text-dark text-nowrap" style="font-size: 0.84rem; white-space: nowrap;">Pusat Persuratan</span>
                    <small class="text-muted text-nowrap mt-1" style="font-size: 0.68rem; white-space: nowrap;">DPRD PROV. JATIM</small>
                </div>
            </a>
        </div>
    @endif

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Page Title -->
        @if (!empty($title) || View::hasSection('page-title'))
            <h1 class="h4 m-0 fw-bold text-dark text-truncate me-2" style="font-size: clamp(0.92rem, 3.2vw, 1.25rem); max-width: calc(100vw - 180px);">@yield('page-title', $title)</h1>
        @endif
        <!-- /Page Title -->

        <ul class="navbar-nav flex-row align-items-center ms-auto gap-1 gap-sm-2">
            <!-- User Profile (Name, Role, Avatar) -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center gap-2 p-1 text-decoration-none" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="text-end d-none d-md-block lh-sm me-1">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem; letter-spacing: -0.2px; max-width: 140px;">{{ $currentUser->nama ?? 'Nama Staff' }}</div>
                        <small class="text-muted" style="font-size: 0.74rem;">{{ $roleName }}</small>
                    </div>
                    <div class="avatar avatar-online">
                        <img src="{{ asset($avatar) }}" alt="Avatar" class="w-px-35 h-px-35 rounded-circle border shadow-xs" style="object-fit: cover;" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar">
                                    <img src="{{ asset($avatar) }}" alt class="w-px-35 h-px-35 rounded-circle" />
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block text-dark">{{ $currentUser->nama ?? 'Nama' }}</span>
                                    <small class="text-muted">{{ $roleName }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('change-password') }}">
                            <i class="bx bx-key me-2"></i>
                            <span class="align-middle">Ubah Password</span>
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
            <!--/ User Profile -->

            <!-- Notification Bell Icon -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown ms-1">
                <a class="nav-link dropdown-toggle hide-arrow position-relative p-2 rounded-circle hover-bg" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Pengajuan">
                    <i class="bx bx-bell fs-4 text-secondary"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notif-bell-badge d-none" style="font-size: 0.65rem; padding: 0.25em 0.45em; transform: translate(-40%, 30%) !important;">
                        0
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0 shadow-lg dropdown-notif-menu">
                    <li class="dropdown-menu-header border-bottom p-3 bg-light rounded-top">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <i class="bx bx-bell text-primary fs-5"></i> Notifikasi
                            </h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-label-primary rounded-pill notif-count-badge">{{ $notifications->count() }} Baru</span>
                                @if ($notifications->count() > 0)
                                    <button type="button" class="btn btn-xs btn-outline-primary btn-mark-all-read py-0 px-2" style="font-size: 0.68rem;" title="Tandai semua telah dibaca">
                                        <i class="bx bx-check-double me-1"></i>Tandai Dibaca
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container" style="max-height: 380px; overflow-y: auto;">
                        <ul class="list-group list-group-flush notif-list-container">
                            @forelse ($notifications as $item)
                                <li class="list-group-item list-group-item-action p-3 border-bottom notif-item" data-notif-id="{{ $item['id'] }}">
                                    <a href="{{ $item['url'] }}" class="text-decoration-none text-dark d-flex align-items-start gap-2 notif-link">
                                        <div class="flex-shrink-0 mt-1">
                                            @if ($item['status'] === 'Disetujui')
                                                <span class="badge bg-label-success p-2 rounded-circle"><i class="bx bx-check fs-6"></i></span>
                                            @elseif ($item['status'] === 'Ditolak')
                                                <span class="badge bg-label-danger p-2 rounded-circle"><i class="bx bx-x fs-6"></i></span>
                                            @else
                                                <span class="badge bg-label-warning p-2 rounded-circle"><i class="bx bx-time-five fs-6"></i></span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark text-truncate" style="max-width: 170px;">{{ $item['title'] }}</small>
                                                <small class="text-muted notif-time" style="font-size: 0.7rem;">{{ $item['time'] ? $item['time']->locale('id')->diffForHumans() : '' }}</small>
                                            </div>
                                            <p class="mb-1 text-muted small text-truncate" style="max-width: 230px; font-size: 0.78rem;">{{ $item['desc'] }}</p>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <span class="badge bg-label-{{ $item['status'] === 'Disetujui' ? 'success' : ($item['status'] === 'Ditolak' ? 'danger' : 'warning') }} py-0 px-2" style="font-size: 0.68rem;">
                                                    {{ $item['status'] }}
                                                </span>
                                                <div class="d-flex align-items-center gap-1">
                                                    @if ($item['user'])
                                                        <small class="text-muted" style="font-size: 0.68rem;">Pengaju: {{ Str::limit($item['user'], 12) }}</small>
                                                    @endif
                                                    <span class="badge p-1 bg-primary rounded-circle notif-unread-dot" title="Belum dibaca" style="width: 6px; height: 6px;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="list-group-item p-4 text-center text-muted">
                                    <i class="bx bx-bell-off fs-1 text-muted mb-2 d-block"></i>
                                    <small>Belum ada update pengajuan terbaru.</small>
                                </li>
                            @endforelse
                        </ul>
                    </li>
                    @if ($notifications->count() > 0)
                        <li class="p-2 border-top text-center bg-light rounded-bottom">
                            <small class="text-muted" style="font-size: 0.72rem;">Klik notifikasi untuk melihat detail surat</small>
                        </li>
                    @endif
                </ul>
            </li>
            <!--/ Notification Bell Icon -->
        </ul>
    </div>
</nav>

<script>
    (function() {
        const userId = '{{ $currentUser->id ?? "guest" }}';
        const storageKey = 'read_notifs_' + userId;

        function getReadNotifs() {
            try {
                return JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                return [];
            }
        }

        function saveReadNotifs(readList) {
            try {
                localStorage.setItem(storageKey, JSON.stringify(readList));
            } catch (e) {}
        }

        function updateNotifBadges() {
            const readList = getReadNotifs();
            let unreadCount = 0;
            const $items = document.querySelectorAll('.notif-item');

            $items.forEach(function(item) {
                const notifId = item.getAttribute('data-notif-id');
                const dot = item.querySelector('.notif-unread-dot');
                if (readList.includes(notifId)) {
                    item.classList.add('bg-lighter', 'opacity-75');
                    if (dot) dot.classList.add('d-none');
                } else {
                    unreadCount++;
                    item.classList.remove('bg-lighter', 'opacity-75');
                    if (dot) dot.classList.remove('d-none');
                }
            });

            const bellBadges = document.querySelectorAll('.notif-bell-badge');
            const countBadges = document.querySelectorAll('.notif-count-badge');
            const markAllBtns = document.querySelectorAll('.btn-mark-all-read');

            bellBadges.forEach(function(badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            });

            countBadges.forEach(function(badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount + ' Baru';
                    badge.classList.remove('bg-label-secondary');
                    badge.classList.add('bg-label-primary');
                } else {
                    badge.textContent = 'Semua Dibaca';
                    badge.classList.remove('bg-label-primary');
                    badge.classList.add('bg-label-secondary');
                }
            });

            markAllBtns.forEach(function(btn) {
                if (unreadCount > 0) {
                    btn.classList.remove('d-none');
                } else {
                    btn.classList.add('d-none');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateNotifBadges();

            // Click individual notif link
            document.querySelectorAll('.notif-item a.notif-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    const item = this.closest('.notif-item');
                    if (item) {
                        const notifId = item.getAttribute('data-notif-id');
                        const readList = getReadNotifs();
                        if (notifId && !readList.includes(notifId)) {
                            readList.push(notifId);
                            saveReadNotifs(readList);
                        }
                    }
                });
            });

            // Mark all read button
            document.querySelectorAll('.btn-mark-all-read').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const readList = getReadNotifs();
                    document.querySelectorAll('.notif-item').forEach(function(item) {
                        const notifId = item.getAttribute('data-notif-id');
                        if (notifId && !readList.includes(notifId)) {
                            readList.push(notifId);
                        }
                    });
                    saveReadNotifs(readList);
                    updateNotifBadges();
                });
            });
        });

        // Also run immediately in case DOM is already ready
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            updateNotifBadges();
        }
    })();
</script>
