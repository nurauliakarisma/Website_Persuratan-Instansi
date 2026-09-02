<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login &mdash; Pusat Persuratan DPRD Prov. Jatim</title>

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
    <link rel="stylesheet" href="{{ asset('dist/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('dist/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('dist/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('dist/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <!-- Helpers -->
    <script src="{{ asset('dist/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('dist/js/config.js') }}"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-2">
                            <img src="{{ asset('images/avatars/logo.png') }}" width="80" height="75" alt="Logo DPRD Jatim">
                        </div>
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">                                 
                                <span class="app-brand-text demo text-body fw-bolder">Pusat Persuratan DPRD Prov. Jatim</span>
                            </a>                            
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-4">Selamat Datang! 👋</h4>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" action="{{ route('login.action') }}"
                            method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan alamat email Anda" value="{{ old('email') }}" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                    {{-- <a href="auth-forgot-password-basic.html">
                                        <small>Forgot Password?</small>
                                    </a> --}}
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            {{-- <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                            </div> --}}
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- System Watermark Badge -->
    <div class="watermark-brand" title="Sistem Resmi - Karya & Hak Cipta Nur Aulia Karisma Dewi">
        <i class="bx bxs-badge-check text-primary"></i>
        <span>SIM-Persuratan &bull; Nur Aulia Karisma Dewi</span>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('dist/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('dist/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('dist/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('dist/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('dist/js/main.js') }}"></script>

    <!-- Page JS -->
    <script>
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
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
