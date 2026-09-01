@extends('layouts.single')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Nomor NPD</h5>
            <h6 class="card-subtitle">No. Sub Kegiatan &rightarrow; No. Rincian Belanja</h6>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 220px;">Nomor NPD & Tanggal</th>
                            <th style="min-width: 320px;">Uraian Kegiatan</th>
                            <th style="min-width: 160px;">Nominal Anggaran</th>
                            <th style="min-width: 150px;">Nama Pengaju</th>
                            <th class="text-center" style="min-width: 140px;">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $npd)
                            <tr data-status="{{ $npd->status }}">
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-primary font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.76rem;">
                                            {{ $npd->nomor }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bx bx-calendar text-primary me-1"></i>{{ \Carbon\Carbon::parse($npd->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 450px;" class="text-wrap m-0">
                                        @if (strlen($npd->uraian_kegiatan) > 120)
                                            <span class="text-preview fw-semibold text-dark" style="font-size: 0.86rem;">{{ Str::limit($npd->uraian_kegiatan, 120) }}</span>
                                            <span class="text-full d-none fw-semibold text-dark" style="font-size: 0.86rem;">{{ $npd->uraian_kegiatan }}</span>
                                            <a href="javascript:void(0);" class="text-primary fw-semibold small ms-1" onclick="$(this).siblings('.text-preview, .text-full').toggleClass('d-none'); $(this).text($(this).text() === 'Lihat Selengkapnya' ? 'Sembunyikan' : 'Lihat Selengkapnya');">Lihat Selengkapnya</a>
                                        @else
                                            <span class="fw-semibold text-dark" style="font-size: 0.86rem;">{{ $npd->uraian_kegiatan }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark fs-6">
                                        Rp{{ number_format($npd->anggaran, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info px-2 py-1" style="font-size: 0.76rem;">{{ $npd->nama_penginput ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="m-0 d-flex align-items-center justify-content-center gap-2">
                                        @if ($npd->status === 'Diajukan')
                                            @if ((auth()->user()->tipe ?? request()->user()->tipe ?? '') === 'Staff')
                                                <span class="badge bg-label-warning">{{ $npd->status }}</span>
                                            @else
                                                <button type="button" class="btn btn-xs btn-outline-success btn-approve"
                                                    data-bs-toggle="modal" data-bs-target="#approvalModal"
                                                    data-action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}"
                                                    data-status="Disetujui"
                                                    data-nomor="{{ $npd->nomor }}"
                                                    data-title="Konfirmasi Persetujuan Surat"
                                                    data-message="Apakah Anda yakin ingin <strong>MENYETUJUI</strong> pengajuan surat ini?"
                                                    data-btn-text="Ya, Setujui"
                                                    data-btn-class="btn-success"
                                                    data-icon="bx-check-circle"
                                                    data-icon-color="text-success">
                                                    <i class="bx bx-check-circle me-1"></i>Setujui
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-danger btn-approve"
                                                    data-bs-toggle="modal" data-bs-target="#approvalModal"
                                                    data-action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}"
                                                    data-status="Ditolak"
                                                    data-nomor="{{ $npd->nomor }}"
                                                    data-title="Konfirmasi Penolakan Surat"
                                                    data-message="Apakah Anda yakin ingin <strong>MENOLAK</strong> pengajuan surat ini?"
                                                    data-btn-text="Ya, Tolak"
                                                    data-btn-class="btn-danger"
                                                    data-icon="bx-x-circle"
                                                    data-icon-color="text-danger">
                                                    <i class="bx bx-x-circle me-1"></i>Tolak
                                                </button>
                                            @endif
                                        @else
                                            <div>
                                                <span class="badge bg-label-{{ $npd->status === 'Disetujui' ? 'success' : 'danger' }}">{{ $npd->status }}</span>
                                                @if ($npd->status === 'Ditolak')
                                                    @php
                                                        $currentUser = auth()->user();
                                                        $isStaff = ($currentUser->tipe ?? '') === 'Staff';
                                                        $isCreator = ($currentUser->nama ?? '') === ($npd->nama_penginput ?? '') || empty($npd->nama_penginput);
                                                        $canResubmit = $isStaff && $isCreator;
                                                    @endphp
                                                    @if ($canResubmit)
                                                        <button type="button" class="btn btn-xs btn-warning mt-1 w-100" data-bs-toggle="modal" data-bs-target="#editModalNpd-{{ $npd->id }}">
                                                            <i class="bx bx-edit me-1"></i>Perbaiki
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="d-md-none mobile-data-list">
                @forelse ($pengajuan as $npd)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-npd-detail"
                        data-status="{{ $npd->status }}">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                            <span class="badge bg-label-primary fw-semibold text-wrap text-start">{{ $npd->nomor }}</span>
                            <span class="badge bg-label-{{ $npd->status === 'Disetujui' ? 'success' : ($npd->status === 'Diajukan' ? 'warning' : 'danger') }} flex-shrink-0">
                                {{ $npd->status }}
                            </span>
                        </div>
                        <div class="fw-bold text-dark fs-6 mb-2">{{ $npd->uraian_kegiatan }}</div>
                        <div class="p-2 bg-lighter rounded mb-2" style="font-size: 0.76rem;">
                            <div class="text-muted mb-1">
                                <i class="bx bx-calendar me-1 text-primary"></i><strong>Tanggal Pengajuan:</strong> {{ \Carbon\Carbon::parse($npd->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-muted mb-1">
                                <i class="bx bx-user me-1 text-info"></i><strong>Staff Pengaju:</strong> {{ $npd->nama_penginput ?? '-' }}
                            </div>
                            <div class="text-muted mb-1">
                                <i class="bx bx-receipt me-1 text-secondary"></i><strong>Kode Rekening:</strong> {{ $npd->alokasi->rincianBelanja->kode_rekening ?? '-' }}
                            </div>
                            <div class="d-flex justify-content-between text-dark pt-1 border-top mt-1">
                                <small class="text-muted">Nominal Anggaran:</small>
                                <strong class="text-primary fs-6">Rp{{ number_format($npd->anggaran, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-1 pt-2 border-top">
                            @if ($npd->status === 'Diajukan')
                                @if ((auth()->user()->tipe ?? request()->user()->tipe ?? '') !== 'Staff')
                                    <button type="button" class="btn btn-xs btn-success px-3 py-1 btn-approve"
                                        data-bs-toggle="modal" data-bs-target="#approvalModal"
                                        data-action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}"
                                        data-status="Disetujui"
                                        data-nomor="{{ $npd->nomor }}"
                                        data-title="Konfirmasi Persetujuan Surat"
                                        data-message="Apakah Anda yakin ingin <strong>MENYETUJUI</strong> pengajuan surat ini?"
                                        data-btn-text="Ya, Setujui"
                                        data-btn-class="btn-success"
                                        data-icon="bx-check-circle"
                                        data-icon-color="text-success">
                                        <i class="bx bx-check me-1"></i>Setujui
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 btn-approve"
                                        data-bs-toggle="modal" data-bs-target="#approvalModal"
                                        data-action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}"
                                        data-status="Ditolak"
                                        data-nomor="{{ $npd->nomor }}"
                                        data-title="Konfirmasi Penolakan Surat"
                                        data-message="Apakah Anda yakin ingin <strong>MENOLAK</strong> pengajuan surat ini?"
                                        data-btn-text="Ya, Tolak"
                                        data-btn-class="btn-danger"
                                        data-icon="bx-x-circle"
                                        data-icon-color="text-danger">
                                        <i class="bx bx-x me-1"></i>Tolak
                                    </button>
                                @endif
                            @elseif ($npd->status === 'Ditolak')
                                @php
                                    $currentUser = auth()->user();
                                    $isStaff = ($currentUser->tipe ?? '') === 'Staff';
                                    $isCreator = ($currentUser->nama ?? '') === ($npd->nama_penginput ?? '') || empty($npd->nama_penginput);
                                    $canResubmit = $isStaff && $isCreator;
                                @endphp
                                @if ($canResubmit)
                                    <button type="button" class="btn btn-xs btn-warning px-3 py-1" data-bs-toggle="modal" data-bs-target="#editModalNpd-{{ $npd->id }}">
                                        <i class="bx bx-edit me-1"></i>Perbaiki Pengajuan
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada riwayat pengajuan NPD untuk alokasi ini.
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

    <!-- Modal Edit & Ajukan Ulang NPD (Khusus yang Ditolak) -->
    @foreach ($pengajuan as $npd)
        @php
            $currentUser = auth()->user();
            $isStaff = ($currentUser->tipe ?? '') === 'Staff';
            $isCreator = ($currentUser->nama ?? '') === ($npd->nama_penginput ?? '') || empty($npd->nama_penginput);
            $canResubmit = $isStaff && $isCreator;
        @endphp
        @if ($npd->status === 'Ditolak' && $canResubmit)
            <div class="modal fade" id="editModalNpd-{{ $npd->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <form action="{{ route('npd.pengajuan.resubmit', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}" method="POST" class="modal-content">
                        @method('put')
                        @csrf
                        <div class="modal-header border-bottom pb-3">
                            <h5 class="modal-title d-flex align-items-center text-warning">
                                <i class="bx bx-edit-alt fs-4 me-2"></i>
                                Perbaiki & Ajukan Ulang NPD
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-3">
                            @if ($npd->catatan_penolakan)
                                <div class="alert alert-danger d-flex align-items-start mb-3" role="alert">
                                    <i class="bx bx-error-circle fs-4 me-2 mt-1"></i>
                                    <div>
                                        <strong class="d-block">Alasan Penolakan dari Admin:</strong>
                                        <p class="mb-0 mt-1">{{ $npd->catatan_penolakan }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nomor_npd_{{ $npd->id }}">Nomor Surat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nomor_npd_{{ $npd->id }}" value="{{ $npd->nomor }}" readonly disabled />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="tanggal_pengajuan_{{ $npd->id }}">Tanggal Pengajuan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="tanggal_pengajuan_{{ $npd->id }}" value="{{ \Carbon\Carbon::parse($npd->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}" readonly />
                                    <input type="hidden" name="tanggal_pengajuan" value="{{ $npd->tanggal_pengajuan }}" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="alokasi_npd_{{ $npd->id }}">Pilih NPD</label>
                                <div class="col-sm-9">
                                    <select name="alokasi_npd_id" id="alokasi_npd_{{ $npd->id }}" class="form-select select-npd-alokasi" data-id="{{ $npd->id }}" required>
                                        <option value="" disabled>Pilih NPD</option>
                                        @foreach ($npds as $alokasiItem)
                                            <option value="{{ $alokasiItem->id }}"
                                                data-program-kode="{{ $alokasiItem->subKegiatan->kode_program ?? '' }}"
                                                data-program-ket="{{ $alokasiItem->subKegiatan->ket_program ?? '' }}"
                                                data-kegiatan-kode="{{ $alokasiItem->subKegiatan->kode_kegiatan ?? '' }}"
                                                data-kegiatan-ket="{{ $alokasiItem->subKegiatan->ket_kegiatan ?? '' }}"
                                                data-subkegiatan-kode="{{ $alokasiItem->subKegiatan->kode_subkegiatan ?? '' }}"
                                                data-subkegiatan-ket="{{ $alokasiItem->subKegiatan->ket_subkegiatan ?? '' }}"
                                                data-rincian-kode="{{ $alokasiItem->rincianBelanja->kode_rekening ?? '' }}"
                                                data-rincian-ket="{{ $alokasiItem->rincianBelanja->keterangan ?? '' }}"
                                                data-sisa="{{ $alokasiItem->total_anggaran - $alokasiItem->realisasi }}"
                                                @selected($npd->alokasi_npd_id == $alokasiItem->id)>
                                                {{ $alokasiItem->subKegiatan->kode_subkegiatan ?? '-' }} - {{ $alokasiItem->subKegiatan->ket_subkegiatan ?? '-' }} |
                                                {{ $alokasiItem->rincianBelanja->kode_rekening ?? '-' }} - {{ $alokasiItem->rincianBelanja->keterangan ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="program_{{ $npd->id }}">Program</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="program_{{ $npd->id }}" value="{{ $npd->alokasi->subKegiatan->kode_program ?? '' }}" placeholder="Kode" disabled />
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="ket_program_{{ $npd->id }}" value="{{ $npd->alokasi->subKegiatan->ket_program ?? '' }}" placeholder="Keterangan" disabled />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="kegiatan_{{ $npd->id }}">Kegiatan</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="kegiatan_{{ $npd->id }}" value="{{ $npd->alokasi->subKegiatan->kode_kegiatan ?? '' }}" placeholder="Kode" disabled />
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="ket_kegiatan_{{ $npd->id }}" value="{{ $npd->alokasi->subKegiatan->ket_kegiatan ?? '' }}" placeholder="Keterangan" disabled />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="subkegiatan_{{ $npd->id }}">Sub Kegiatan</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="subkegiatan_{{ $npd->id }}" value="{{ $npd->alokasi->subKegiatan->kode_subkegiatan ?? '' }}" placeholder="Kode" disabled />
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="ket_subkegiatan_{{ $npd->id }}" value="{{ $npd->alokasi->subKegiatan->ket_subkegiatan ?? '' }}" placeholder="Keterangan" disabled />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="rincian_belanja_{{ $npd->id }}">Rincian Belanja</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="rincian_belanja_{{ $npd->id }}" value="{{ $npd->alokasi->rincianBelanja->kode_rekening ?? '' }}" placeholder="Kode" disabled />
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="ket_rincian_belanja_{{ $npd->id }}" value="{{ $npd->alokasi->rincianBelanja->keterangan ?? '' }}" placeholder="Keterangan" disabled />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="uraian_kegiatan_{{ $npd->id }}">Uraian Kegiatan <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <textarea name="uraian_kegiatan" id="uraian_kegiatan_{{ $npd->id }}" class="form-control" rows="3" required>{{ old('uraian_kegiatan', $npd->uraian_kegiatan) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="anggaran_{{ $npd->id }}">Anggaran <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" step="any" min="1" class="form-control" id="anggaran_{{ $npd->id }}" name="anggaran" value="{{ old('anggaran', $npd->anggaran) }}" required />
                                        <input type="hidden" name="sisa" id="sisa_{{ $npd->id }}" value="{{ ($npd->alokasi->total_anggaran ?? 0) - ($npd->alokasi->realisasi ?? 0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label" for="nama_penginput_{{ $npd->id }}">Nama Penginput</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_penginput_{{ $npd->id }}" name="nama_penginput" value="{{ $npd->nama_penginput ?? auth()->user()->nama }}" readonly />
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

            // Dynamic Alokasi Selection Change in NPD Edit Modal
            $(document).on('change', '.select-npd-alokasi', function() {
                const id = $(this).data('id');
                const selectedOpt = $(this).find('option:selected');

                $('#program_' + id).val(selectedOpt.data('program-kode') || '');
                $('#ket_program_' + id).val(selectedOpt.data('program-ket') || '');
                $('#kegiatan_' + id).val(selectedOpt.data('kegiatan-kode') || '');
                $('#ket_kegiatan_' + id).val(selectedOpt.data('kegiatan-ket') || '');
                $('#subkegiatan_' + id).val(selectedOpt.data('subkegiatan-kode') || '');
                $('#ket_subkegiatan_' + id).val(selectedOpt.data('subkegiatan-ket') || '');
                $('#rincian_belanja_' + id).val(selectedOpt.data('rincian-kode') || '');
                $('#ket_rincian_belanja_' + id).val(selectedOpt.data('rincian-ket') || '');
                $('#sisa_' + id).val(selectedOpt.data('sisa') || 0);
            });
        });
    </script>
@endpush
