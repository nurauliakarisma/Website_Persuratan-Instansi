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

    <button type="button" data-bs-toggle="modal" data-bs-target="#addSubKegiatanModal"
        class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Sub Kegiatan
    </button>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-list-check text-primary fs-5"></i> Master Sub Kegiatan
            </h6>
            <span class="badge bg-label-primary rounded-pill px-2 py-1">{{ $subs->count() }} Sub Kegiatan</span>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 250px;">Program</th>
                            <th style="min-width: 260px;">Kegiatan</th>
                            <th style="min-width: 280px;">Sub Kegiatan</th>
                            <th class="text-center" style="min-width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subs as $key => $sub)
                            <tr class="table-row-action" style="cursor: pointer;"
                                data-edit="#editSubKegiatanModal-{{ $sub->id }}"
                                data-delete-action="{{ route('master.sub-kegiatan.destroy', $sub->id) }}"
                                data-title="{{ $sub->kode_subkegiatan }} - {{ $sub->ket_subkegiatan }}"
                                data-subtitle="Kegiatan: {{ $sub->kode_kegiatan }} {{ $sub->ket_kegiatan }}">
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-secondary font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.75rem;">
                                            {{ $sub->kode_program }}
                                        </span>
                                        <span class="text-dark" style="font-size: 0.84rem; line-height: 1.35;">
                                            {{ $sub->ket_program }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-info font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.75rem;">
                                            {{ $sub->kode_kegiatan }}
                                        </span>
                                        <span class="text-dark" style="font-size: 0.84rem; line-height: 1.35;">
                                            {{ $sub->ket_kegiatan }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-primary font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.75rem;">
                                            {{ $sub->kode_subkegiatan }}
                                        </span>
                                        <span class="fw-bold text-dark" style="font-size: 0.86rem; line-height: 1.35;">
                                            {{ $sub->ket_subkegiatan }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1" onclick="event.stopPropagation();">
                                        <button type="button" class="btn btn-xs btn-outline-warning px-2 py-1"
                                            href="#editSubKegiatanModal-{{ $sub->id }}" data-bs-toggle="modal" title="Edit">
                                            <i class="bx bx-edit-alt me-1"></i>Edit
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1 btn-delete"
                                            href="#deleteModal" data-bs-toggle="modal"
                                            data-action="{{ route('master.sub-kegiatan.destroy', $sub->id) }}" title="Hapus">
                                            <i class="bx bx-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="d-md-none mobile-data-list">
                @forelse ($subs as $sub)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-action"
                        data-edit="#editSubKegiatanModal-{{ $sub->id }}"
                        data-delete-action="{{ route('master.sub-kegiatan.destroy', $sub->id) }}"
                        data-title="{{ $sub->kode_subkegiatan }} - {{ $sub->ket_subkegiatan }}"
                        data-subtitle="Kegiatan: {{ $sub->kode_kegiatan }} {{ $sub->ket_kegiatan }}"
                        style="cursor: pointer;">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                            <div>
                                <span class="badge bg-label-primary fw-semibold mb-1">{{ $sub->kode_subkegiatan }}</span>
                                <div class="fw-bold text-dark fs-6">{{ $sub->ket_subkegiatan }}</div>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-shrink-0" title="Klik untuk opsi">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                        </div>
                        <div class="p-2 bg-lighter rounded mb-2" style="font-size: 0.76rem;">
                            <div class="text-muted mb-1">
                                <i class="bx bx-folder me-1 text-primary"></i><strong>Kegiatan:</strong> {{ $sub->kode_kegiatan }} - {{ $sub->ket_kegiatan }}
                            </div>
                            <div class="text-muted">
                                <i class="bx bx-layer me-1 text-secondary"></i><strong>Program:</strong> {{ $sub->kode_program }} - {{ $sub->ket_program }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top" onclick="event.stopPropagation();">
                            <button type="button" class="btn btn-xs btn-outline-warning px-3 py-1 fw-medium d-flex align-items-center"
                                href="#editSubKegiatanModal-{{ $sub->id }}" data-bs-toggle="modal">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 fw-medium d-flex align-items-center btn-delete"
                                href="#deleteModal" data-bs-toggle="modal"
                                data-action="{{ route('master.sub-kegiatan.destroy', $sub->id) }}">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada data sub kegiatan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Action Modal (Pop-up Pilihan Aksi Data) -->
    <div class="modal fade" id="quickActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
                <div class="modal-header border-bottom py-3 px-3">
                    <h6 class="modal-title mb-0 fw-bold d-flex align-items-center text-dark">
                        <i class="bx bx-menu-alt-left fs-4 me-2 text-primary"></i>
                        Pilihan & Aksi Data
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="p-2 bg-lighter rounded border mb-3">
                        <div class="fw-bold text-dark fs-6" id="qaTitle">-</div>
                        <small class="text-muted d-block mt-1" id="qaSubtitle">-</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" id="qaBtnEdit">
                            <i class="bx bx-edit-alt fs-5"></i> Edit Sub Kegiatan
                        </button>
                        <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" id="qaBtnDelete">
                            <i class="bx bx-trash fs-5"></i> Hapus Sub Kegiatan
                        </button>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-3 px-3">
                    <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Sub Kegiatam --}}
    <div class="modal fade" id="addSubKegiatanModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('master.sub-kegiatan.store') }}" method="POST" class="modal-content form-confirm"
                data-confirm-title="Konfirmasi Tambah Sub Kegiatan"
                data-confirm-text="Apakah Anda yakin ingin menambahkan Sub Kegiatan baru ini?"
                data-confirm-btn="<i class='bx bx-plus-circle me-1'></i>Ya, Simpan">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Sub Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="program">Program <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="program" name="kode_program"
                                placeholder="Kode" required />
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="ket_program" name="ket_program"
                                placeholder="Keterangan Program" required />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="kegiatan">Kegiatan <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="kegiatan" name="kode_kegiatan"
                                placeholder="Kode" required />
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="ket_kegiatan" name="ket_kegiatan"
                                placeholder="Keterangan Kegiatan" required />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="subkegiatan">Sub Kegiatan <span class="text-danger">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="subkegiatan" name="kode_subkegiatan"
                                placeholder="Kode" required />
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="ket_subkegiatan" name="ket_subkegiatan"
                                placeholder="Keterangan Sub Kegiatan" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Sub Kegiatam --}}
    @foreach ($subs as $key => $sub)
        <div class="modal fade" id="editSubKegiatanModal-{{ $sub->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('master.sub-kegiatan.update', $sub->id) }}" method="POST" class="modal-content form-confirm"
                    data-confirm-title="Konfirmasi Simpan Perubahan"
                    data-confirm-text="Apakah Anda yakin ingin menyimpan perubahan Sub Kegiatan <strong>{{ $sub->kode_subkegiatan }}</strong>?"
                    data-confirm-btn="<i class='bx bx-save me-1'></i>Ya, Simpan Perubahan">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Sub Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label" for="program_{{ $sub->id }}">Program</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="program_{{ $sub->id }}" name="kode_program"
                                    placeholder="Kode" value="{{ $sub->kode_program }}" disabled />
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="ket_program_{{ $sub->id }}" name="ket_program"
                                    placeholder="Keterangan" value="{{ $sub->ket_program }}" required />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label" for="kegiatan_{{ $sub->id }}">Kegiatan</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="kegiatan_{{ $sub->id }}" name="kode_kegiatan"
                                    placeholder="Kode" value="{{ $sub->kode_kegiatan }}" disabled />
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="ket_kegiatan_{{ $sub->id }}" name="ket_kegiatan"
                                    placeholder="Keterangan" value="{{ $sub->ket_kegiatan }}" required />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label" for="subkegiatan_{{ $sub->id }}">Sub Kegiatan</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="subkegiatan_{{ $sub->id }}" name="kode_subkegiatan"
                                    placeholder="Kode" value="{{ $sub->kode_subkegiatan }}" disabled />
                            </div>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="ket_subkegiatan_{{ $sub->id }}" name="ket_subkegiatan"
                                    placeholder="Keterangan" value="{{ $sub->ket_subkegiatan }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Delete Sub Kegiatan --}}
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center text-danger">
                        <i class="bx bx-trash fs-4 me-2"></i>
                        Konfirmasi Hapus Sub Kegiatan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-2 fs-6 text-dark">Apakah Anda yakin ingin menghapus data Sub Kegiatan ini?</p>
                    <small class="text-danger"><i class="bx bx-error-circle me-1"></i>Tindakan ini akan menghapus seluruh data pengajuan yang terkait.</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            // Delete button click
            $(document).on('click', '.btn-delete', function(e) {
                e.stopPropagation();
                const actionUrl = $(this).data('action');
                $('#deleteModal form').attr('action', actionUrl);
            });

            // Quick Action Modal click handler for cards & table rows
            $(document).on('click', '.card-item-action, .table-row-action', function(e) {
                // Don't trigger if clicked on a button or link
                if ($(e.target).closest('button, a, input').length) {
                    return;
                }

                const editTarget = $(this).data('edit');
                const deleteAction = $(this).data('delete-action');
                const title = $(this).data('title');
                const subtitle = $(this).data('subtitle');

                $('#qaTitle').text(title);
                $('#qaSubtitle').text(subtitle);

                // Setup edit button
                $('#qaBtnEdit').off('click').on('click', function() {
                    $('#quickActionModal').modal('hide');
                    setTimeout(() => {
                        $(editTarget).modal('show');
                    }, 350);
                });

                // Setup delete button
                $('#qaBtnDelete').off('click').on('click', function() {
                    $('#quickActionModal').modal('hide');
                    $('#deleteModal form').attr('action', deleteAction);
                    setTimeout(() => {
                        $('#deleteModal').modal('show');
                    }, 350);
                });

                $('#quickActionModal').modal('show');
            });
        });
    </script>
@endpush
