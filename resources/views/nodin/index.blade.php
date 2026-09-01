@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('nodin.pengajuan', ['bagian' => $bagian]) }}" class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Pengajuan Nota Dinas
    </a>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-notepad text-primary fs-5"></i> Data NODIN
            </h6>
            <a href="{{ route('nodin.export', ['bagian' => $bagian]) }}" target="_blank"
                class="btn btn-sm btn-success d-flex align-items-center gap-1">
                <i class="bx bx-spreadsheet"></i> Export Excel
            </a>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 220px;">Nomor Surat & Tanggal</th>
                            <th style="min-width: 320px;">Subject & Perihal</th>
                            <th style="min-width: 220px;">Pengaju & Atas Nama</th>
                            <th class="text-center" style="min-width: 140px;">Status</th>
                            <th class="text-center" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nodins as $nodin)
                            <tr data-status="{{ $nodin->status }}">
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-primary font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.76rem;">
                                            {{ $nodin->nomor }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bx bx-calendar text-primary me-1"></i>{{ \Carbon\Carbon::parse($nodin->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark mb-1" style="font-size: 0.88rem; line-height: 1.35;">
                                            {{ $nodin->subject }}
                                        </span>
                                        <div style="font-size: 0.82rem; line-height: 1.4;" class="text-muted text-wrap m-0">
                                            @if (strlen($nodin->perihal) > 110)
                                                <span class="text-preview">{{ Str::limit($nodin->perihal, 110) }}</span>
                                                <span class="text-full d-none">{{ $nodin->perihal }}</span>
                                                <a href="javascript:void(0);" class="text-primary fw-semibold small ms-1" onclick="$(this).siblings('.text-preview, .text-full').toggleClass('d-none'); $(this).text($(this).text() === 'Lihat Selengkapnya' ? 'Sembunyikan' : 'Lihat Selengkapnya');">Lihat Selengkapnya</a>
                                            @else
                                                {{ $nodin->perihal }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="mb-1">
                                            <small class="text-muted">Pengaju:</small>
                                            <span class="badge bg-label-info px-2 py-1" style="font-size: 0.74rem;">{{ $nodin->nama_penginput ?? '-' }}</span>
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 0.78rem;">
                                            <i class="bx bx-buildings text-secondary me-1"></i><strong>Atas Nama:</strong> {{ $nodin->atas_nama }}
                                        </small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="m-0 d-flex flex-column align-items-center justify-content-center gap-1">
                                        <span class="badge bg-label-{{ $nodin->status === 'Disetujui' ? 'success' : ($nodin->status === 'Diajukan' ? 'warning' : 'danger') }} px-2 py-1">
                                            {{ $nodin->status }}
                                        </span>
                                        @if ($nodin->status === 'Diajukan' && (auth()->user()->tipe ?? request()->user()->tipe ?? '') !== 'Staff')
                                            <div class="d-flex align-items-center gap-1 mt-1">
                                                <button type="button" class="btn btn-xs btn-success px-2 py-1 btn-approve"
                                                    data-bs-toggle="modal" data-bs-target="#approvalModal"
                                                    data-action="{{ route('nodin.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}"
                                                    data-status="Disetujui"
                                                    data-nomor="{{ $nodin->nomor }}"
                                                    data-title="Konfirmasi Persetujuan Surat"
                                                    data-message="Apakah Anda yakin ingin <strong>MENYETUJUI</strong> surat NODIN ini?"
                                                    data-btn-text="Ya, Setujui"
                                                    data-btn-class="btn-success"
                                                    data-icon="bx-check-circle"
                                                    data-icon-color="text-success"
                                                    title="Setujui Surat">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1 btn-approve"
                                                    data-bs-toggle="modal" data-bs-target="#approvalModal"
                                                    data-action="{{ route('nodin.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}"
                                                    data-status="Ditolak"
                                                    data-nomor="{{ $nodin->nomor }}"
                                                    data-title="Konfirmasi Penolakan Surat"
                                                    data-message="Apakah Anda yakin ingin <strong>MENOLAK</strong> surat NODIN ini?"
                                                    data-btn-text="Ya, Tolak"
                                                    data-btn-class="btn-danger"
                                                    data-icon="bx-x-circle"
                                                    data-icon-color="text-danger"
                                                    title="Tolak Surat">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                        @elseif ($nodin->status === 'Ditolak')
                                            @php
                                                $currentUser = auth()->user();
                                                $isStaff = ($currentUser->tipe ?? '') === 'Staff';
                                                $isCreator = ($currentUser->nama ?? '') === ($nodin->nama_penginput ?? '') || empty($nodin->nama_penginput);
                                                $canResubmit = $isStaff && $isCreator;
                                            @endphp
                                            @if ($canResubmit)
                                                <button type="button" class="btn btn-xs btn-warning mt-1 px-2 py-1" data-bs-toggle="modal" data-bs-target="#editModalNodin-{{ $nodin->id }}">
                                                    <i class="bx bx-edit me-1"></i>Perbaiki
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-xs btn-outline-primary px-3 py-1" data-bs-toggle="modal"
                                        data-bs-target="#detailModal-{{ $nodin->id }}">
                                        <i class="bx bx-info-circle me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="d-md-none mobile-data-list">
                @forelse ($nodins as $nodin)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-nodin"
                        data-status="{{ $nodin->status }}"
                        style="cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#detailModal-{{ $nodin->id }}">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                            <span class="badge bg-label-primary fw-semibold text-wrap text-start">{{ $nodin->nomor }}</span>
                            <span class="badge bg-label-{{ $nodin->status === 'Disetujui' ? 'success' : ($nodin->status === 'Diajukan' ? 'warning' : 'danger') }} flex-shrink-0">
                                {{ $nodin->status }}
                            </span>
                        </div>
                        <div class="fw-bold text-dark fs-6 mb-2">{{ $nodin->perihal }}</div>
                        <div class="p-2 bg-lighter rounded mb-2" style="font-size: 0.76rem;">
                            <div class="text-muted mb-1">
                                <i class="bx bx-calendar me-1 text-primary"></i><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($nodin->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-muted mb-1">
                                <i class="bx bx-user me-1 text-info"></i><strong>Pengaju:</strong> {{ $nodin->nama_penginput ?? '-' }}
                            </div>
                            <div class="text-muted">
                                <i class="bx bx-buildings me-1 text-secondary"></i><strong>Atas Nama:</strong> {{ $nodin->atas_nama }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-2 pt-2 border-top flex-wrap" onclick="event.stopPropagation();">
                            <button type="button" class="btn btn-xs btn-outline-primary px-2 py-1 fw-medium d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#detailModal-{{ $nodin->id }}">
                                <i class="bx bx-info-circle me-1"></i>Detail
                            </button>
                            <div class="d-flex align-items-center gap-1">
                                @if ($nodin->status === 'Diajukan')
                                    @if ((auth()->user()->tipe ?? request()->user()->tipe ?? '') !== 'Staff')
                                        <button type="button" class="btn btn-xs btn-success px-2 py-1 btn-approve"
                                            data-bs-toggle="modal" data-bs-target="#approvalModal"
                                            data-action="{{ route('nodin.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}"
                                            data-status="Disetujui"
                                            data-nomor="{{ $nodin->nomor }}"
                                            data-title="Konfirmasi Persetujuan Surat"
                                            data-message="Apakah Anda yakin ingin <strong>MENYETUJUI</strong> surat NODIN ini?"
                                            data-btn-text="Ya, Setujui"
                                            data-btn-class="btn-success"
                                            data-icon="bx-check-circle"
                                            data-icon-color="text-success">
                                            <i class="bx bx-check me-1"></i>Setujui
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1 btn-approve"
                                            data-bs-toggle="modal" data-bs-target="#approvalModal"
                                            data-action="{{ route('nodin.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}"
                                            data-status="Ditolak"
                                            data-nomor="{{ $nodin->nomor }}"
                                            data-title="Konfirmasi Penolakan Surat"
                                            data-message="Apakah Anda yakin ingin <strong>MENOLAK</strong> surat NODIN ini?"
                                            data-btn-text="Ya, Tolak"
                                            data-btn-class="btn-danger"
                                            data-icon="bx-x-circle"
                                            data-icon-color="text-danger">
                                            <i class="bx bx-x me-1"></i>Tolak
                                        </button>
                                    @endif
                                @elseif ($nodin->status === 'Ditolak')
                                    @php
                                        $currentUser = auth()->user();
                                        $isStaff = ($currentUser->tipe ?? '') === 'Staff';
                                        $isCreator = ($currentUser->nama ?? '') === ($nodin->nama_penginput ?? '') || empty($nodin->nama_penginput);
                                        $canResubmit = $isStaff && $isCreator;
                                    @endphp
                                    @if ($canResubmit)
                                        <button type="button" class="btn btn-xs btn-warning px-2 py-1" data-bs-toggle="modal" data-bs-target="#editModalNodin-{{ $nodin->id }}">
                                            <i class="bx bx-edit me-1"></i>Perbaiki
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada surat NODIN yang diajukan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Approval (Setujui / Tolak) -->
    <div class="modal fade" id="approvalModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="POST" class="modal-content">
                @method('put')
                @csrf
                <input type="hidden" name="status" id="approvalStatusInput" value="">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center" id="approvalModalTitle">
                        <i class="bx fs-4 me-2" id="approvalModalIcon"></i>
                        <span id="approvalModalHeading">Konfirmasi Status</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p id="approvalModalMessage" class="mb-2 fs-6 text-dark"></p>
                    <div class="p-2 rounded bg-lighter border mb-3">
                        <small class="text-muted d-block">Nomor Surat:</small>
                        <span class="fw-bold text-dark font-monospace" id="approvalModalNomor">-</span>
                    </div>

                    <!-- Kolom Alasan/Deskripsi Perbaikan Khusus Tolak -->
                    <div id="rejectionReasonWrapper" class="d-none">
                        <label for="catatan_penolakan" class="form-label fw-semibold text-danger">
                            <i class="bx bx-edit-alt me-1"></i>Catatan / Alasan Penolakan untuk Perbaikan: <span class="text-danger">*</span>
                        </label>
                        <textarea name="catatan_penolakan" id="catatan_penolakan" class="form-control" rows="3" placeholder="Tuliskan catatan perbaikan atau alasan penolakan surat ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn" id="approvalSubmitBtn">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail & Modal Edit NODIN -->
    @foreach ($nodins as $nodin)
        <div class="modal fade" id="detailModal-{{ $nodin->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="detailModalLabel-{{ $nodin->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
                    <div class="modal-header border-bottom py-3 px-3">
                        <h6 class="modal-title mb-0 fw-bold d-flex align-items-center text-dark" id="detailModalLabel-{{ $nodin->id }}">
                            <i class="bx bx-file text-primary fs-4 me-2"></i>
                            Detail Nota Dinas
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3 p-sm-4">
                        @if ($nodin->status === 'Ditolak' && $nodin->catatan_penolakan)
                            <div class="alert alert-danger d-flex align-items-start mb-3" role="alert">
                                <i class="bx bx-error-circle fs-4 me-2 mt-1"></i>
                                <div>
                                    <strong class="d-block">Catatan Penolakan / Perbaikan:</strong>
                                    <p class="mb-0 mt-1">{{ $nodin->catatan_penolakan }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
                            <span class="badge bg-label-primary fs-6 fw-semibold text-wrap text-start">{{ $nodin->nomor }}</span>
                            <span class="badge bg-label-{{ $nodin->status === 'Disetujui' ? 'success' : ($nodin->status === 'Diajukan' ? 'warning' : 'danger') }} rounded-pill px-2 py-1">
                                {{ $nodin->status }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block fw-semibold mb-1">Subject / Judul Kegiatan:</small>
                            <div class="fw-bold text-dark fs-6">{{ $nodin->subject }}</div>
                        </div>

                        <div class="p-2 p-sm-3 bg-lighter rounded border mb-3" style="font-size: 0.85rem;">
                            <div class="mb-2">
                                <i class="bx bx-calendar me-1 text-primary"></i><strong>Tanggal Kegiatan:</strong>
                                <span class="text-dark d-block ms-4">
                                    {{ \Carbon\Carbon::parse($nodin->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($nodin->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <i class="bx bx-buildings me-1 text-secondary"></i><strong>Atas Nama:</strong>
                                <span class="text-dark d-block ms-4">{{ $nodin->atas_nama }}</span>
                            </div>
                            <div>
                                <i class="bx bx-user me-1 text-info"></i><strong>Staff Pengaju:</strong>
                                <span class="text-dark d-block ms-4">{{ $nodin->nama_penginput ?? '-' }}</span>
                            </div>
                        </div>

                        <div>
                            <small class="text-muted d-block fw-semibold mb-1">Perihal Lengkap:</small>
                            <div class="p-3 bg-white rounded border text-dark text-wrap" style="line-height: 1.6; font-size: 0.88rem;">
                                {{ $nodin->perihal }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3 px-3">
                        <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @php
            $currentUser = auth()->user();
            $isStaff = ($currentUser->tipe ?? '') === 'Staff';
            $isCreator = ($currentUser->nama ?? '') === ($nodin->nama_penginput ?? '') || empty($nodin->nama_penginput);
            $canResubmit = $isStaff && $isCreator;
        @endphp

        @if ($nodin->status === 'Ditolak' && $canResubmit)
            <div class="modal fade" id="editModalNodin-{{ $nodin->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <form action="{{ route('nodin.pengajuan.resubmit', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}" method="POST" class="modal-content">
                        @method('put')
                        @csrf
                        <div class="modal-header border-bottom pb-3">
                            <h5 class="modal-title d-flex align-items-center text-warning">
                                <i class="bx bx-edit-alt fs-4 me-2"></i>
                                Perbaiki & Ajukan Ulang Nota Dinas
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-3">
                            @if ($nodin->catatan_penolakan)
                                <div class="alert alert-danger d-flex align-items-start mb-3" role="alert">
                                    <i class="bx bx-error-circle fs-4 me-2 mt-1"></i>
                                    <div>
                                        <strong class="d-block">Alasan Penolakan dari Admin:</strong>
                                        <p class="mb-0 mt-1">{{ $nodin->catatan_penolakan }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nomor_nodin_idx_{{ $nodin->id }}">Nomor Surat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nomor_nodin_idx_{{ $nodin->id }}" value="{{ $nodin->nomor }}" readonly disabled />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="tanggal_pengajuan_idx_{{ $nodin->id }}">Tanggal Pengajuan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tanggal_pengajuan_idx_{{ $nodin->id }}" value="{{ \Carbon\Carbon::parse($nodin->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}" readonly />
                                    <input type="hidden" name="tanggal_pengajuan" value="{{ $nodin->tanggal_pengajuan }}" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="index_kegiatan_idx_{{ $nodin->id }}">Index Kegiatan</label>
                                <div class="col-sm-9">
                                    <select name="index_kegiatan_id" id="index_kegiatan_idx_{{ $nodin->id }}" class="form-select" required>
                                        <option value="" disabled>Pilih Index Kegiatan</option>
                                        @foreach ($indexes as $idx)
                                            <option value="{{ $idx->id }}" data-nomor="{{ $idx->kode }}" @selected($nodin->index_kegiatan_id == $idx->id)>
                                                {{ $idx->kode }} - {{ $idx->keterangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="subkegiatan_idx_{{ $nodin->id }}">Sub Kegiatan</label>
                                <div class="col-sm-9">
                                    <select name="subkegiatan_id" id="subkegiatan_idx_{{ $nodin->id }}" class="form-select" required>
                                        <option value="" disabled>Pilih Sub Kegiatan</option>
                                        @foreach ($subkegiatans as $sub)
                                            <option value="{{ $sub->id }}" @selected($nodin->subkegiatan_id == $sub->id)>
                                                {{ $sub->kode_subkegiatan }} - {{ $sub->ket_subkegiatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="rincian_belanja_idx_{{ $nodin->id }}">Rincian Belanja</label>
                                <div class="col-sm-9">
                                    <select name="rincian_belanja_id" id="rincian_belanja_idx_{{ $nodin->id }}" class="form-select" required>
                                        <option value="" disabled>Pilih Rincian Belanja</option>
                                        @foreach ($rincians as $rinci)
                                            <option value="{{ $rinci->id }}" @selected($nodin->rincian_belanja_id == $rinci->id)>
                                                {{ $rinci->kode_rekening }} - {{ $rinci->keterangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="subject_idx_{{ $nodin->id }}">Subject</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="subject_idx_{{ $nodin->id }}" name="subject" value="{{ old('subject', $nodin->subject) }}" required />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="perihal_idx_{{ $nodin->id }}">Perihal</label>
                                <div class="col-sm-9">
                                    <textarea name="perihal" id="perihal_idx_{{ $nodin->id }}" class="form-control" rows="3" required>{{ old('perihal', $nodin->perihal) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="tanggal_mulai_idx_{{ $nodin->id }}">Tanggal Mulai</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control" id="tanggal_mulai_idx_{{ $nodin->id }}" name="tanggal_mulai" value="{{ old('tanggal_mulai', $nodin->tanggal_mulai) }}" required />
                                </div>
                                <label class="col-sm-2 col-form-label text-md-end" for="tanggal_selesai_idx_{{ $nodin->id }}">Selesai</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="tanggal_selesai_idx_{{ $nodin->id }}" name="tanggal_selesai" value="{{ old('tanggal_selesai', $nodin->tanggal_selesai) }}" required />
                                </div>
                            </div>

                            @php
                                $selectedStaffs = $nodin->staff_list;
                            @endphp

                            <div class="row mb-2">
                                <label class="col-sm-3 col-form-label" for="atas_nama_idx_{{ $nodin->id }}">Atas Nama</label>
                                <div class="col-sm-9 an-dynamic-container" id="an-container-idx-{{ $nodin->id }}">
                                    @foreach ($selectedStaffs as $sIndex => $selectedStaff)
                                        @if ($sIndex === 0)
                                            <div class="mb-2">
                                                <select name="atas_nama[]" class="form-select" required>
                                                    <option value="" disabled @selected(empty($selectedStaff))>Pilih Staff</option>
                                                    @foreach ($staffs as $staff)
                                                        <option value="{{ $staff->nama }}" @selected($selectedStaff === $staff->nama)>{{ $staff->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <div class="input-group mb-2">
                                                <select name="atas_nama[]" class="form-select" required>
                                                    <option value="" disabled @selected(empty($selectedStaff))>Pilih Staff</option>
                                                    @foreach ($staffs as $staff)
                                                        <option value="{{ $staff->nama }}" @selected($selectedStaff === $staff->nama)>{{ $staff->nama }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline-danger btn-remove-staff">&dash;</button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100 btn-add-staff" data-target="#an-container-idx-{{ $nodin->id }}">&plus; Tambah Staff</button>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama_penginput_idx_{{ $nodin->id }}">Nama Penginput</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_penginput_idx_{{ $nodin->id }}" name="nama_penginput" value="{{ $nodin->nama_penginput ?? auth()->user()->nama }}" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top pt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-paper-plane me-1"></i>Simpan & Ajukan Ulang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Template clone untuk tambah staff dinamis --}}
    <div class="input-group mb-2" id="an-template-staff" style="display: none;">
        <select name="atas_nama[]" class="form-select">
            <option value="" disabled selected>Pilih Staff</option>
            @foreach ($staffs as $staff)
                <option value="{{ $staff->nama }}">{{ $staff->nama }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-outline-danger btn-remove-staff">&dash;</button>
    </div>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $('.btn-approve').click(function() {
                const actionUrl = $(this).data('action');
                const status = $(this).data('status');
                const nomor = $(this).data('nomor') || '-';
                const title = $(this).data('title');
                const message = $(this).data('message');
                const btnText = $(this).data('btn-text');
                const btnClass = $(this).data('btn-class');
                const icon = $(this).data('icon');
                const iconColor = $(this).data('icon-color');

                $('#approvalModal form').attr('action', actionUrl);
                $('#approvalStatusInput').val(status);
                $('#approvalModalHeading').text(title);
                $('#approvalModalMessage').html(message);
                $('#approvalModalNomor').text(nomor);
                $('#approvalModalIcon').attr('class', 'bx fs-4 me-2 ' + icon + ' ' + iconColor);
                
                if (status === 'Ditolak') {
                    $('#rejectionReasonWrapper').removeClass('d-none');
                    $('#catatan_penolakan').prop('required', true).val('').focus();
                } else {
                    $('#rejectionReasonWrapper').addClass('d-none');
                    $('#catatan_penolakan').prop('required', false).val('');
                }

                $('#approvalSubmitBtn')
                    .text(btnText)
                    .removeClass('btn-primary btn-success btn-danger')
                    .addClass(btnClass);
            });

            // Dynamic Staff Tambah / Hapus
            $(document).on('click', '.btn-add-staff', function() {
                const targetContainer = $(this).data('target');
                const clone = $('#an-template-staff').clone().removeAttr('id').show();
                clone.find('select').prop('required', true);
                $(targetContainer).append(clone);
            });

            $(document).on('click', '.btn-remove-staff', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
@endpush
