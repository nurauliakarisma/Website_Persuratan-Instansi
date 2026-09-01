@extends('layouts.single')

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

    <div class="card mt-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">Publikasi Media</h5>
            </div>
            @if ($prev_url)
                <a href="{{ $prev_url }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i>Kembali
                </a>
            @endif
        </div>
        <div class="card-body">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th style="min-width: 200px;">Nama Mitra Media</th>
                            <th style="min-width: 280px;">Judul Publikasi / Berita</th>
                            <th style="min-width: 150px;">Tanggal Tayang</th>
                            <th style="min-width: 140px;">Biaya Publikasi</th>
                            <th style="min-width: 130px;">Fotocopy</th>
                            <th style="min-width: 140px;">Pengaju</th>
                            <th class="text-center" style="min-width: 140px;">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuan as $num => $publikasi)
                            <tr data-status="{{ $publikasi->status }}">
                                <td><span class="text-muted fw-semibold">{{ ++$num }}</span></td>
                                <td>
                                    <span class="badge bg-label-primary fs-6 fw-semibold">
                                        {{ $publikasi->media->nama ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark" style="font-size: 0.88rem; line-height: 1.35;">
                                        {{ $publikasi->judul }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bx bx-calendar text-primary me-1"></i>{{ \Carbon\Carbon::parse($publikasi->tanggal_tayang)->locale('id')->translatedFormat('d F Y') }}
                                    </small>
                                </td>
                                <td><span class="fw-bold text-dark">Rp{{ number_format($publikasi->nominal_publikasi, 0, ',', '.') }}</span></td>
                                <td><span class="text-muted">Rp{{ number_format($publikasi->nominal_fotocopy, 0, ',', '.') }}</span></td>
                                <td>
                                    <span class="badge bg-label-info">{{ $publikasi->nama_penginput ?? '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="m-0 d-flex align-items-center justify-content-center gap-1">
                                        @if ($publikasi->status === 'Diajukan')
                                            @if ((auth()->user()->tipe ?? request()->user()->tipe ?? '') === 'Staff')
                                                <span class="badge bg-label-warning">{{ $publikasi->status }}</span>
                                            @else
                                                <button type="button" class="btn btn-xs btn-outline-success btn-approve"
                                                    data-bs-toggle="modal" data-bs-target="#approvalModal"
                                                    data-action="{{ route('media.pengajuan.approve', $publikasi->id) }}"
                                                    data-status="Disetujui"
                                                    data-nomor="{{ $publikasi->judul }}"
                                                    data-title="Konfirmasi Persetujuan Publikasi"
                                                    data-message="Apakah Anda yakin ingin <strong>MENYETUJUI</strong> pengajuan publikasi media ini?"
                                                    data-btn-text="Ya, Setujui"
                                                    data-btn-class="btn-success"
                                                    data-icon="bx-check-circle"
                                                    data-icon-color="text-success">
                                                    <i class="bx bx-check-circle me-1"></i>Setujui
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-danger btn-approve"
                                                    data-bs-toggle="modal" data-bs-target="#approvalModal"
                                                    data-action="{{ route('media.pengajuan.approve', $publikasi->id) }}"
                                                    data-status="Ditolak"
                                                    data-nomor="{{ $publikasi->judul }}"
                                                    data-title="Konfirmasi Penolakan Publikasi"
                                                    data-message="Apakah Anda yakin ingin <strong>MENOLAK</strong> pengajuan publikasi media ini?"
                                                    data-btn-text="Ya, Tolak"
                                                    data-btn-class="btn-danger"
                                                    data-icon="bx-x-circle"
                                                    data-icon-color="text-danger">
                                                    <i class="bx bx-x-circle me-1"></i>Tolak
                                                </button>
                                            @endif
                                        @else
                                            <div>
                                                <span class="badge bg-label-{{ $publikasi->status === 'Disetujui' ? 'success' : 'danger' }}">{{ $publikasi->status }}</span>
                                                @if ($publikasi->status === 'Ditolak')
                                                    @php
                                                        $currentUser = auth()->user();
                                                        $isStaff = ($currentUser->tipe ?? '') === 'Staff';
                                                        $isCreator = ($currentUser->nama ?? '') === ($publikasi->nama_penginput ?? '') || empty($publikasi->nama_penginput);
                                                        $canResubmit = $isStaff && $isCreator;
                                                    @endphp
                                                    @if ($canResubmit)
                                                        <button type="button" class="btn btn-xs btn-warning mt-1 w-100" data-bs-toggle="modal" data-bs-target="#editModalMedia-{{ $publikasi->id }}">
                                                            <i class="bx bx-edit me-1"></i>Perbaiki
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data Pengajuan Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="d-md-none mobile-data-list">
                @forelse ($pengajuan as $num => $publikasi)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-media-detail"
                        data-status="{{ $publikasi->status }}">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                            <div>
                                <span class="badge bg-label-primary fw-semibold mb-1">{{ $publikasi->media->nama ?? '-' }}</span>
                                <div class="fw-bold text-dark fs-6">{{ $publikasi->judul }}</div>
                            </div>
                            <span class="badge bg-label-{{ $publikasi->status === 'Disetujui' ? 'success' : ($publikasi->status === 'Diajukan' ? 'warning' : 'danger') }} flex-shrink-0">
                                {{ $publikasi->status }}
                            </span>
                        </div>
                        <div class="p-2 bg-lighter rounded mb-2" style="font-size: 0.76rem;">
                            <div class="text-muted mb-1">
                                <i class="bx bx-calendar me-1 text-primary"></i><strong>Tanggal Tayang:</strong> {{ \Carbon\Carbon::parse($publikasi->tanggal_tayang)->locale('id')->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-muted mb-1">
                                <i class="bx bx-user me-1 text-info"></i><strong>Pengaju:</strong> {{ $publikasi->nama_penginput ?? '-' }}
                            </div>
                            <div class="d-flex justify-content-between text-dark pt-1 border-top mt-1">
                                <div><small class="text-muted">Publikasi:</small><br><strong>Rp{{ number_format($publikasi->nominal_publikasi, 0, ',', '.') }}</strong></div>
                                <div class="text-end"><small class="text-muted">Fotocopy:</small><br><strong>Rp{{ number_format($publikasi->nominal_fotocopy, 0, ',', '.') }}</strong></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-1 pt-2 border-top">
                            @if ($publikasi->status === 'Diajukan')
                                @if ((auth()->user()->tipe ?? request()->user()->tipe ?? '') !== 'Staff')
                                    <button type="button" class="btn btn-xs btn-success px-3 py-1 btn-approve"
                                        data-bs-toggle="modal" data-bs-target="#approvalModal"
                                        data-action="{{ route('media.pengajuan.approve', $publikasi->id) }}"
                                        data-status="Disetujui"
                                        data-nomor="{{ $publikasi->judul }}"
                                        data-title="Konfirmasi Persetujuan Publikasi"
                                        data-message="Apakah Anda yakin ingin <strong>MENYETUJUI</strong> pengajuan publikasi media ini?"
                                        data-btn-text="Ya, Setujui"
                                        data-btn-class="btn-success"
                                        data-icon="bx-check-circle"
                                        data-icon-color="text-success">
                                        <i class="bx bx-check me-1"></i>Setujui
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 btn-approve"
                                        data-bs-toggle="modal" data-bs-target="#approvalModal"
                                        data-action="{{ route('media.pengajuan.approve', $publikasi->id) }}"
                                        data-status="Ditolak"
                                        data-nomor="{{ $publikasi->judul }}"
                                        data-title="Konfirmasi Penolakan Publikasi"
                                        data-message="Apakah Anda yakin ingin <strong>MENOLAK</strong> pengajuan publikasi media ini?"
                                        data-btn-text="Ya, Tolak"
                                        data-btn-class="btn-danger"
                                        data-icon="bx-x-circle"
                                        data-icon-color="text-danger">
                                        <i class="bx bx-x me-1"></i>Tolak
                                    </button>
                                @endif
                            @elseif ($publikasi->status === 'Ditolak')
                                @php
                                    $currentUser = auth()->user();
                                    $isStaff = ($currentUser->tipe ?? '') === 'Staff';
                                    $isCreator = ($currentUser->nama ?? '') === ($publikasi->nama_penginput ?? '') || empty($publikasi->nama_penginput);
                                    $canResubmit = $isStaff && $isCreator;
                                @endphp
                                @if ($canResubmit)
                                    <button type="button" class="btn btn-xs btn-warning px-3 py-1" data-bs-toggle="modal" data-bs-target="#editModalMedia-{{ $publikasi->id }}">
                                        <i class="bx bx-edit me-1"></i>Perbaiki Pengajuan
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Data pengajuan kosong.
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
                        <small class="text-muted d-block">Judul Publikasi:</small>
                        <span class="fw-bold text-dark" id="approvalModalNomor">-</span>
                    </div>

                    <!-- Kolom Alasan/Deskripsi Perbaikan Khusus Tolak -->
                    <div id="rejectionReasonWrapper" class="d-none">
                        <label for="catatan_penolakan" class="form-label fw-semibold text-danger">
                            <i class="bx bx-edit-alt me-1"></i>Catatan / Alasan Penolakan untuk Perbaikan: <span class="text-danger">*</span>
                        </label>
                        <textarea name="catatan_penolakan" id="catatan_penolakan" class="form-control" rows="3" placeholder="Tuliskan catatan perbaikan atau alasan penolakan..."></textarea>
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

    <!-- Modal Edit & Ajukan Ulang Media (Khusus yang Ditolak) -->
    @foreach ($pengajuan as $publikasi)
        @php
            $currentUser = auth()->user();
            $isStaff = ($currentUser->tipe ?? '') === 'Staff';
            $isCreator = ($currentUser->nama ?? '') === ($publikasi->nama_penginput ?? '') || empty($publikasi->nama_penginput);
            $canResubmit = $isStaff && $isCreator;
        @endphp
        @if ($publikasi->status === 'Ditolak' && $canResubmit)
            <div class="modal fade" id="editModalMedia-{{ $publikasi->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <form action="{{ route('media.pengajuan.resubmit', $publikasi->id) }}" method="POST" class="modal-content">
                        @method('put')
                        @csrf
                        <div class="modal-header border-bottom pb-3">
                            <h5 class="modal-title d-flex align-items-center text-warning">
                                <i class="bx bx-edit-alt fs-4 me-2"></i>
                                Perbaiki & Ajukan Ulang Publikasi Media
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-3">
                            @if ($publikasi->catatan_penolakan)
                                <div class="alert alert-danger d-flex align-items-start mb-3" role="alert">
                                    <i class="bx bx-error-circle fs-4 me-2 mt-1"></i>
                                    <div>
                                        <strong class="d-block">Catatan Penolakan / Alasan Perbaikan dari Admin:</strong>
                                        <p class="mb-0 mt-1">{{ $publikasi->catatan_penolakan }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="media_id_{{ $publikasi->id }}">Pilih Media</label>
                                <div class="col-sm-9">
                                    <select name="media_id" id="media_id_{{ $publikasi->id }}" class="form-select" required>
                                        <option value="" disabled>Pilih Media</option>
                                        @foreach ($medias as $med)
                                            <option value="{{ $med->id }}" @selected($publikasi->media_id == $med->id)>
                                                {{ $med->nama }} | Rp {{ number_format($med->harga_total, 2, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="tanggal_tayang_{{ $publikasi->id }}">Tanggal Tayang</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" id="tanggal_tayang_{{ $publikasi->id }}" name="tanggal_tayang" value="{{ old('tanggal_tayang', $publikasi->tanggal_tayang) }}" required />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nominal_publikasi_{{ $publikasi->id }}">Nominal Publikasi</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="nominal_publikasi_{{ $publikasi->id }}" name="nominal_publikasi" value="{{ old('nominal_publikasi', $publikasi->nominal_publikasi) }}" required />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nominal_fotocopy_{{ $publikasi->id }}">Nominal Fotocopy</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="nominal_fotocopy_{{ $publikasi->id }}" name="nominal_fotocopy" value="{{ old('nominal_fotocopy', $publikasi->nominal_fotocopy) }}" required />
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="judul_{{ $publikasi->id }}">Judul Publikasi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="judul_{{ $publikasi->id }}" name="judul" value="{{ old('judul', $publikasi->judul) }}" required />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama_penginput_{{ $publikasi->id }}">Nama Pengaju</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_penginput_{{ $publikasi->id }}" name="nama_penginput" value="{{ $publikasi->nama_penginput ?? auth()->user()->nama }}" readonly />
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
        });
    </script>
@endpush
