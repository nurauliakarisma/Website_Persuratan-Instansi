@php
    $hasMenu = false;
@endphp
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('page-title', $title) &mdash; Pusat Persuratan DPRD Prov. Jatim</title>

    <meta name="description" content="Sistem Informasi Manajemen Persuratan & Pengelolaan Anggaran Sekretariat DPRD Provinsi Jawa Timur" />
    <meta name="author" content="Nur Aulia Karisma Dewi" />
    <meta name="copyright" content="© 2026 Nur Aulia Karisma Dewi. All rights reserved." />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/avatars/logo.png') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/avatars/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('dist/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="{{ asset('dist/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('dist/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('dist/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('dist/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->
    @stack('page-css')

    <!-- Helpers -->
    <script src="{{ asset('dist/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('dist/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar layout-without-menu">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.partials.navbar', ['hasMenu' => false])
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if ($prev_url)
                            <a href="{{ $prev_url }}" class="d-flex align-items-center gap-2 mb-3">
                                <i class="bx bx-arrow-back"></i>
                                <span>Kembali</span>
                            </a>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                <i class="bx bx-check-circle me-1"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <i class="bx bx-error me-1"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.partials.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- System Watermark Badge -->
        <div class="watermark-brand" title="Sistem Resmi - Karya & Hak Cipta Nur Aulia Karisma Dewi">
            <i class="bx bxs-badge-check text-primary"></i>
            <span>SIM-Persuratan &bull; Nur Aulia Karisma Dewi</span>
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('dist/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('dist/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('dist/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>

    <script src="{{ asset('dist/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('dist/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('dist/js/main.js') }}"></script>

    <!-- Page JS -->
    <script>
        $(document).ready(function() {
            // Set default datatable config
            $.extend($.fn.dataTable.defaults, {
                ordering: false,
                autoWidth: false,
                language: {
                    search: "",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 entri",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ditemukan data yang sesuai",
                    paginate: {
                        first: "«",
                        previous: "‹",
                        next: "›",
                        last: "»"
                    }
                }
            });
            // Set datatable to table
            $('.table').each(function() {
                const $table = $(this);
                const dt = $table.DataTable();
                const $container = $table.closest('.dt-container, .dataTables_wrapper');

                // Check if table has rows with data-status
                const hasStatus = $table.find('tbody tr[data-status]').length > 0;
                if (hasStatus) {
                    const $searchWrapper = $container.find('.dt-search, .dataTables_filter');
                    if ($searchWrapper.length && !$searchWrapper.find('.dt-filter-dropdown-btn').length) {
                        $searchWrapper.addClass('d-flex align-items-center gap-2 justify-content-end position-relative');

                        const filterHtml = `
                            <div class="dropdown dt-filter-dropdown">
                                <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 dt-filter-dropdown-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Filter Status">
                                    <i class="bx bx-filter-alt fs-5"></i>
                                    <span class="d-none d-md-inline" style="font-size: 0.8rem;">Filter</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm py-2" style="min-width: 180px; z-index: 1055;">
                                    <li><h6 class="dropdown-header text-uppercase text-muted py-1" style="font-size: 0.7rem;"><i class="bx bx-filter me-1"></i>Filter Status</h6></li>
                                    <li><button type="button" class="dropdown-item dt-filter-item active d-flex align-items-center justify-content-between py-1" data-status=""><span>Semua Status</span><i class="bx bx-check dt-check"></i></button></li>
                                    <li><button type="button" class="dropdown-item dt-filter-item d-flex align-items-center justify-content-between py-1 text-warning" data-status="Diajukan"><span><i class="bx bxs-circle me-1" style="font-size: 8px;"></i>Diajukan</span><i class="bx bx-check dt-check d-none"></i></button></li>
                                    <li><button type="button" class="dropdown-item dt-filter-item d-flex align-items-center justify-content-between py-1 text-success" data-status="Disetujui"><span><i class="bx bxs-circle me-1" style="font-size: 8px;"></i>Disetujui</span><i class="bx bx-check dt-check d-none"></i></button></li>
                                    <li><button type="button" class="dropdown-item dt-filter-item d-flex align-items-center justify-content-between py-1 text-danger" data-status="Ditolak"><span><i class="bx bxs-circle me-1" style="font-size: 8px;"></i>Ditolak</span><i class="bx bx-check dt-check d-none"></i></button></li>
                                </ul>
                            </div>
                        `;
                        $searchWrapper.append(filterHtml);

                        const $dropdown = $searchWrapper.find('.dt-filter-dropdown');
                        const $filterBtn = $dropdown.find('.dt-filter-dropdown-btn');

                        $dropdown.find('.dt-filter-item').on('click', function(e) {
                            e.preventDefault();
                            const selectedStatus = $(this).data('status');

                            $dropdown.find('.dt-filter-item').removeClass('active');
                            $dropdown.find('.dt-check').addClass('d-none');

                            $(this).addClass('active');
                            $(this).find('.dt-check').removeClass('d-none');

                            if (selectedStatus) {
                                $filterBtn.removeClass('btn-outline-secondary').addClass('btn-primary');
                            } else {
                                $filterBtn.removeClass('btn-primary').addClass('btn-outline-secondary');
                            }

                            // Custom filter function for this table
                            $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(fn => fn._tableNode !== $table[0]);
                            if (selectedStatus) {
                                const customSearch = function(settings, data, dataIndex, rowData, counter) {
                                    if (settings.nTable !== $table[0]) return true;
                                    const rowNode = settings.aoData[dataIndex].nTr;
                                    const rowStatus = $(rowNode).attr('data-status');
                                    return rowStatus === selectedStatus;
                                };
                                customSearch._tableNode = $table[0];
                                $.fn.dataTable.ext.search.push(customSearch);
                            }

                            dt.draw();
                        });
                    }
                }
            });
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Global SweetAlert2 Form Confirmation
            $(document).on('submit', '.form-confirm', function(e) {
                const $form = $(this);
                if ($form.data('confirmed')) {
                    return true;
                }

                // Check HTML5 validity first
                if (this.checkValidity && !this.checkValidity()) {
                    this.reportValidity();
                    return false;
                }

                e.preventDefault();

                const title = $form.data('confirm-title') || 'Konfirmasi Simpan Data';
                const text = $form.data('confirm-text') || 'Apakah Anda yakin ingin menyimpan data ini?';
                const btnText = $form.data('confirm-btn') || '<i class="bx bx-check me-1"></i>Ya, Simpan';
                const icon = $form.data('confirm-icon') || 'question';

                Swal.fire({
                    title: title,
                    html: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: btnText,
                    cancelButtonText: '<i class="bx bx-x me-1"></i>Batal',
                    customClass: {
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-outline-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.data('confirmed', true);
                        HTMLFormElement.prototype.submit.call($form[0]);
                    }
                });
            });

            // Global SweetAlert2 Delete Confirmation
            $(document).on('submit', '.form-confirm-delete', function(e) {
                const $form = $(this);
                if ($form.data('confirmed')) {
                    return true;
                }
                e.preventDefault();

                const title = $form.data('confirm-title') || 'Konfirmasi Hapus Data';
                const text = $form.data('confirm-text') || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                const btnText = $form.data('confirm-btn') || '<i class="bx bx-trash me-1"></i>Ya, Hapus';

                Swal.fire({
                    title: title,
                    html: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: btnText,
                    cancelButtonText: '<i class="bx bx-x me-1"></i>Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger me-2',
                        cancelButton: 'btn btn-outline-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.data('confirmed', true);
                        HTMLFormElement.prototype.submit.call($form[0]);
                    }
                });
            });

            // Global Password Visibility Toggle
            $(document).on('click', '.form-password-toggle .input-group-text', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $container = $(this).closest('.form-password-toggle, .input-group');
                const $input = $container.find('input');
                const $icon = $(this).find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('bx-hide').addClass('bx-show');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('bx-show').addClass('bx-hide');
                }
            });

            // Developer Watermark & System Integrity Console Stamp
            console.log(
                "%c🏛️ SIM-PERSURATAN SEKRETARIAT DPRD PROV. JATIM %c\n\n" +
                "🔒 Sistem Informasi Manajemen Persuratan & Pengelolaan Anggaran\n" +
                "👤 Hak Cipta & Dikembangkan Oleh: Nur Aulia Karisma Dewi\n" +
                "📅 Tahun Rilis: 2026 (Versi 2.0 Official)\n" +
                "⚖️ Seluruh hak cipta dilindungi undang-undang. Dilarang keras menggandakan / mengkloning tanpa izin resmi.\n",
                "background: #1E3A8A; color: #FFFFFF; font-size: 13px; font-weight: bold; padding: 6px 12px; border-radius: 4px;",
                "color: #1E293B; font-size: 11px; font-family: monospace;"
            );
        });
    </script>
    @stack('page-js')

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
