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

    <button type="button" data-bs-toggle="modal" data-bs-target="#addRincianBelanjaModal"
        class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Rincian Belanja
    </button>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-receipt text-primary fs-5"></i> Master Rincian Belanja
            </h6>
            <span class="badge bg-label-primary rounded-pill px-2 py-1">{{ $rincians->count() }} Rekening</span>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 220px;">Kode Rekening</th>
                            <th style="min-width: 450px;">Uraian Rincian Belanja</th>
                            <th class="text-center" style="min-width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rincians as $key => $rincian)
                            <tr class="table-row-action" style="cursor: pointer;"
                                data-edit="#editRincianBelanjaModal-{{ $rincian->id }}"
                                data-delete-action="{{ route('master.rincian-belanja.destroy', $rincian->id) }}"
                                data-title="{{ $rincian->kode_rekening }}"
                                data-subtitle="{{ $rincian->keterangan }}">
                                <td>
                                    <span class="badge bg-label-primary font-monospace px-2 py-1" style="font-size: 0.8rem;">
                                        {{ $rincian->kode_rekening }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark" style="font-size: 0.88rem; line-height: 1.4;">
                                        {{ $rincian->keterangan }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1" onclick="event.stopPropagation();">
                                        <button type="button" class="btn btn-xs btn-outline-warning px-2 py-1"
                                            href="#editRincianBelanjaModal-{{ $rincian->id }}" data-bs-toggle="modal" title="Edit">
                                            <i class="bx bx-edit-alt me-1"></i>Edit
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1 btn-delete"
                                            href="#deleteModal" data-bs-toggle="modal"
                                            data-action="{{ route('master.rincian-belanja.destroy', $rincian->id) }}" title="Hapus">
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
                @forelse ($rincians as $rincian)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-action"
                        data-edit="#editRincianBelanjaModal-{{ $rincian->id }}"
                        data-delete-action="{{ route('master.rincian-belanja.destroy', $rincian->id) }}"
                        data-title="{{ $rincian->kode_rekening }}"
                        data-subtitle="{{ $rincian->keterangan }}"
                        style="cursor: pointer;">
                        <div class="d-flex align-items-start justify-content-between mb-1 gap-2">
                            <div>
                                <span class="badge bg-label-primary fw-semibold mb-1">{{ $rincian->kode_rekening }}</span>
                                <div class="fw-bold text-dark fs-6">{{ $rincian->keterangan }}</div>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-secondary flex-shrink-0" title="Klik untuk opsi">
                                <i class="bx bx-dots-vertical-rounded fs-5"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 mt-2 border-top" onclick="event.stopPropagation();">
                            <button type="button" class="btn btn-xs btn-outline-warning px-3 py-1 fw-medium d-flex align-items-center"
                                href="#editRincianBelanjaModal-{{ $rincian->id }}" data-bs-toggle="modal">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 fw-medium d-flex align-items-center btn-delete"
                                href="#deleteModal" data-bs-toggle="modal"
                                data-action="{{ route('master.rincian-belanja.destroy', $rincian->id) }}">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada data rincian belanja.
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
                            <i class="bx bx-edit-alt fs-5"></i> Edit Rincian Belanja
                        </button>
                        <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold" id="qaBtnDelete">
                            <i class="bx bx-trash fs-5"></i> Hapus Rincian Belanja
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
    {{-- Tambah Rincian Belanja --}}
    <div class="modal fade" id="addRincianBelanjaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('master.rincian-belanja.store') }}" method="POST" class="modal-content form-confirm"
                data-confirm-title="Konfirmasi Tambah Rincian Belanja"
                data-confirm-text="Apakah Anda yakin ingin menambahkan Rincian Belanja baru ini?"
                data-confirm-btn="<i class='bx bx-plus-circle me-1'></i>Ya, Simpan">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Rincian Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="kode_rekening">Kode Rekening <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kode_rekening" name="kode_rekening" placeholder="Contoh: 5.1.02.01.01.0024" required />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="keterangan">Rincian Belanja <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Nama / Uraian Rincian Belanja" required />
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

    {{-- Edit Rincian Belanja --}}
    @foreach ($rincians as $key => $rincian)
        <div class="modal fade" id="editRincianBelanjaModal-{{ $rincian->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('master.rincian-belanja.update', $rincian->id) }}" method="POST"
                    class="modal-content form-confirm"
                    data-confirm-title="Konfirmasi Simpan Perubahan"
                    data-confirm-text="Apakah Anda yakin ingin menyimpan perubahan Rincian Belanja <strong>{{ $rincian->kode_rekening }}</strong>?"
                    data-confirm-btn="<i class='bx bx-save me-1'></i>Ya, Simpan Perubahan">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Rincian Belanja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label" for="kode_{{ $rincian->id }}">Kode Rekening</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="kode_{{ $rincian->id }}" name="kode_rekening"
                                    value="{{ $rincian->kode_rekening }}" required />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label" for="keterangan_{{ $rincian->id }}">Rincian Belanja</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="keterangan_{{ $rincian->id }}" name="keterangan"
                                    value="{{ $rincian->keterangan }}" required />
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

    {{-- Delete Rincian Belanja --}}
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center text-danger">
                        <i class="bx bx-trash fs-4 me-2"></i>
                        Konfirmasi Hapus Rincian Belanja
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-2 fs-6 text-dark">Apakah Anda yakin ingin menghapus data rincian belanja ini?</p>
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
