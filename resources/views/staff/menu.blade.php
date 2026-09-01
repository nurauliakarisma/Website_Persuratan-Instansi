@extends('layouts.single')

@section('content')
    @if (in_array($bagian, ['BagianDokinfo', 'Dokinfo', 'A']))
        <div class="text-center mb-4 mt-2">
            <span class="badge bg-label-info mb-2 px-3 py-2 fw-semibold"><i class="bx bx-folder-open me-1"></i>Bagian Dokinfo</span>
            <h4 class="fw-bold text-dark mb-1">Layanan Persuratan Dokinfo</h4>
            <p class="text-muted small mb-0">Pilih jenis surat untuk membuat pengajuan atau melihat riwayat nomor surat</p>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header text-center border-bottom pb-2">
                        <h4 class="card-title fw-bold text-dark mb-0"><i class="bx bx-file text-primary me-1"></i>NPD</h4>
                        <small class="text-muted">Nota Permintaan Dana</small>
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-2 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded mb-2" style="height: 140px;">
                            <img src="{{ asset('images/dokinfo_cover.jpg') }}" alt="cover npd" class="w-100 h-100 rounded" style="object-fit: cover;">
                        </div>
                        <a href="{{ route('npd.pengajuan', 'BagianDokinfo') }}" class="btn btn-warning fw-semibold">
                            <i class="bx bx-plus-circle me-1"></i>Ajukan Nomor NPD
                        </a>
                        <a href="{{ route('npd.detail', ['bagian' => 'BagianDokinfo', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary fw-semibold">
                            <i class="bx bx-list-ul me-1"></i>Lihat Nomor NPD
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header text-center border-bottom pb-2">
                        <h4 class="card-title fw-bold text-dark mb-0"><i class="bx bx-envelope text-primary me-1"></i>NODIN</h4>
                        <small class="text-muted">Nota Dinas</small>
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-2 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded mb-2" style="height: 140px;">
                            <img src="{{ asset('images/dokinfo_cover.jpg') }}" alt="cover nodin" class="w-100 h-100 rounded" style="object-fit: cover;">
                        </div>
                        <a href="{{ route('nodin.pengajuan', 'BagianDokinfo') }}" class="btn btn-warning fw-semibold">
                            <i class="bx bx-plus-circle me-1"></i>Ajukan Nomor NODIN
                        </a>
                        <a href="{{ route('nodin.detail', ['bagian' => 'BagianDokinfo', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary fw-semibold">
                            <i class="bx bx-list-ul me-1"></i>Lihat Nomor NODIN
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header text-center border-bottom pb-2">
                        <h4 class="card-title fw-bold text-dark mb-0"><i class="bx bx-news text-primary me-1"></i>MEDIA</h4>
                        <small class="text-muted">Publikasi Media</small>
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-2 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded mb-2" style="height: 140px;">
                            <img src="{{ asset('images/dokinfo_cover.jpg') }}" alt="cover media" class="w-100 h-100 rounded" style="object-fit: cover;">
                        </div>
                        <a href="{{ route('media.create') }}" class="btn btn-warning fw-semibold">
                            <i class="bx bx-plus-circle me-1"></i>Ajukan Publikasi Media
                        </a>
                        <a href="{{ route('media.show', ['prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary fw-semibold">
                            <i class="bx bx-list-ul me-1"></i>Lihat Publikasi Media
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif (in_array($bagian, ['BagianFPP', 'FPP', 'B']))
        <div class="text-center mb-4 mt-2">
            <span class="badge bg-label-warning mb-2 px-3 py-2 fw-semibold"><i class="bx bx-folder-open me-1"></i>Bagian FPP</span>
            <h4 class="fw-bold text-dark mb-1">Layanan Persuratan FPP</h4>
            <p class="text-muted small mb-0">Pilih jenis surat untuk membuat pengajuan atau melihat riwayat nomor surat</p>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header text-center border-bottom pb-2">
                        <h4 class="card-title fw-bold text-dark mb-0"><i class="bx bx-file text-warning me-1"></i>NPD</h4>
                        <small class="text-muted">Nota Permintaan Dana</small>
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-2 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded mb-2" style="height: 140px;">
                            <img src="{{ asset('images/fpp_cover.jpg') }}" alt="cover npd" class="w-100 h-100 rounded" style="object-fit: cover;">
                        </div>
                        <a href="{{ route('npd.pengajuan', 'BagianFPP') }}" class="btn btn-warning fw-semibold">
                            <i class="bx bx-plus-circle me-1"></i>Ajukan Nomor NPD
                        </a>
                        <a href="{{ route('npd.detail', ['bagian' => 'BagianFPP', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary fw-semibold">
                            <i class="bx bx-list-ul me-1"></i>Lihat Nomor NPD
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header text-center border-bottom pb-2">
                        <h4 class="card-title fw-bold text-dark mb-0"><i class="bx bx-envelope text-warning me-1"></i>NODIN</h4>
                        <small class="text-muted">Nota Dinas</small>
                    </div>
                    <div class="card-body p-3 d-flex flex-column gap-2 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded mb-2" style="height: 140px;">
                            <img src="{{ asset('images/fpp_cover.jpg') }}" alt="cover nodin" class="w-100 h-100 rounded" style="object-fit: cover;">
                        </div>
                        <a href="{{ route('nodin.pengajuan', 'BagianFPP') }}" class="btn btn-warning fw-semibold">
                            <i class="bx bx-plus-circle me-1"></i>Ajukan Nomor NODIN
                        </a>
                        <a href="{{ route('nodin.detail', ['bagian' => 'BagianFPP', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary fw-semibold">
                            <i class="bx bx-list-ul me-1"></i>Lihat Nomor NODIN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center mb-4 mt-2">
            <h3 class="fw-bold text-dark mb-1">Pilih Bagian Persuratan</h3>
            <p class="text-muted fs-6 mb-0">Silakan pilih bagian untuk membuat pengajuan atau melihat data nomor surat</p>
        </div>
        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card h-100 shadow-sm border-0 transition-all">
                    <div class="card-body p-4 d-flex flex-column gap-3 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded shadow-xs" style="height: 220px;">
                            <img src="{{ asset('images/dokinfo_cover.jpg') }}" alt="Bagian Dokinfo" class="w-100 h-100 rounded" style="object-fit: cover; transition: transform 0.3s ease;">
                        </div>
                        <div class="text-center pt-2">
                            <h4 class="card-title fw-bold text-primary mb-1">Bagian Dokinfo</h4>
                            <p class="text-muted small mb-3">Dokumentasi, Informasi, dan Publikasi Media</p>
                            <a href="{{ route('menu.index', ['bagian' => 'BagianDokinfo', 'prev_url' => route('menu.index')]) }}"
                                class="btn btn-primary w-100 fw-semibold">
                                <i class="bx bx-right-arrow-circle me-1"></i>Masuk ke Bagian Dokinfo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card h-100 shadow-sm border-0 transition-all">
                    <div class="card-body p-4 d-flex flex-column gap-3 align-items-stretch justify-content-between">
                        <div class="overflow-hidden rounded shadow-xs" style="height: 220px;">
                            <img src="{{ asset('images/fpp_cover.jpg') }}" alt="Bagian FPP" class="w-100 h-100 rounded" style="object-fit: cover; transition: transform 0.3s ease;">
                        </div>
                        <div class="text-center pt-2">
                            <h4 class="card-title fw-bold text-warning mb-1">Bagian FPP</h4>
                            <p class="text-muted small mb-3">Fasilitasi Penganggaran dan Pengawasan</p>
                            <a href="{{ route('menu.index', ['bagian' => 'BagianFPP', 'prev_url' => route('menu.index')]) }}"
                                class="btn btn-warning w-100 fw-semibold">
                                <i class="bx bx-right-arrow-circle me-1"></i>Masuk ke Bagian FPP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
