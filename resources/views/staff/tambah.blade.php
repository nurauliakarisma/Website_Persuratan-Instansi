@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <div class="fw-bold mb-1"><i class="bx bx-error-circle me-1"></i> Terjadi kesalahan pengisian data:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button type="button" data-bs-toggle="modal" data-bs-target="#addStaffModal"
        class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Staff Baru
    </button>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-user-pin text-primary fs-5"></i> Data Staff
            </h6>
            <span class="badge bg-label-primary rounded-pill px-2 py-1">{{ $staffs->count() }} Pegawai</span>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffs as $staff)
                            <tr>
                                <td>{{ $staff->nip ?? '-' }}</td>
                                <td><strong>{{ $staff->nama }}</strong></td>
                                <td>{{ $staff->email }}</td>
                                <td><span class="badge bg-label-info">{{ $staff->jabatan ?? '-' }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <a class="btn btn-xs btn-outline-warning" href="#editStaffModal-{{ $staff->id }}"
                                            data-bs-toggle="modal" role="button">
                                            <i class="bx bx-edit-alt me-1"></i>Edit
                                        </a>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-delete-staff"
                                            data-bs-toggle="modal" data-bs-target="#deleteStaffModal"
                                            data-action="{{ route('user.staff.hapus', $staff->id) }}"
                                            data-nama="{{ $staff->nama }}"
                                            data-nip="{{ $staff->nip ?? '-' }}"
                                            data-jabatan="{{ $staff->jabatan ?? '-' }}">
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
                @forelse ($staffs as $staff)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs">
                        <div class="d-flex align-items-start justify-content-between mb-1 gap-2">
                            <div>
                                <div class="fw-bold text-dark fs-6">{{ $staff->nama }}</div>
                                @if ($staff->nip)
                                    <small class="text-muted d-block" style="font-size: 0.76rem;">NIP: {{ $staff->nip }}</small>
                                @endif
                                <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.78rem;">
                                    <i class="bx bx-envelope text-secondary"></i> {{ $staff->email }}
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-label-info fw-semibold text-truncate d-inline-block" style="font-size: 0.72rem; max-width: 130px;" title="{{ $staff->jabatan ?? 'Staf' }}">{{ $staff->jabatan ?? 'Staf' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 mt-2 border-top">
                            <a class="btn btn-xs btn-outline-warning px-3 py-1 fw-medium d-flex align-items-center"
                                href="#editStaffModal-{{ $staff->id }}" data-bs-toggle="modal">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 fw-medium d-flex align-items-center btn-delete-staff"
                                data-bs-toggle="modal" data-bs-target="#deleteStaffModal"
                                data-action="{{ route('user.staff.hapus', $staff->id) }}"
                                data-nama="{{ $staff->nama }}"
                                data-nip="{{ $staff->nip ?? '-' }}"
                                data-jabatan="{{ $staff->jabatan ?? '-' }}">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada data staf.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Staff -->
    <div class="modal fade" id="deleteStaffModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="deleteStaffForm" action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center text-danger">
                        <i class="bx bx-trash fs-4 me-2"></i>
                        Konfirmasi Hapus Staff
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-2 fs-6 text-dark">Apakah Anda yakin ingin menghapus data staf berikut?</p>
                    <div class="p-3 rounded bg-lighter border mb-2">
                        <div class="fw-bold text-dark fs-6" id="deleteStaffNama">-</div>
                        <small class="text-muted d-block" id="deleteStaffNip">NIP: -</small>
                        <small class="text-muted d-block" id="deleteStaffJabatan">Jabatan: -</small>
                    </div>
                    <small class="text-danger"><i class="bx bx-error-circle me-1"></i>Tindakan ini tidak dapat dibatalkan.</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>Ya, Hapus Staff
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Staff --}}
    <div class="modal fade" id="addStaffModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('user.staff.tambah') }}" method="POST" class="modal-content form-confirm"
                data-confirm-title="Konfirmasi Tambah Staff" data-confirm-text="Apakah Anda yakin ingin menambahkan data staf baru ini?" data-confirm-btn="<i class='bx bx-plus-circle me-1'></i>Ya, Tambah Staff">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Form Tambah Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_nip">NIP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_nip" name="nip" placeholder="Contoh: 199108162015031001" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_nama" name="nama" required placeholder="Contoh: Ahmad Fauzi, S.STP" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_email">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="add_email" name="email" required placeholder="email@contoh.go.id" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_password">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" class="form-control" id="add_password" name="password" required minlength="6" placeholder="Minimal 6 karakter" autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer toggle-password" style="cursor: pointer;"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_jabatan">Jabatan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_jabatan" name="jabatan" placeholder="Contoh: Pengadministrasi Persuratan" />
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

    {{-- Edit Staff --}}
    @foreach ($staffs as $staffItem)
        <div class="modal fade" id="editStaffModal-{{ $staffItem->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('user.staff.ubah', $staffItem->id) }}" method="POST"
                    class="modal-content form-confirm"
                    data-confirm-title="Konfirmasi Simpan Perubahan"
                    data-confirm-text="Apakah Anda yakin ingin menyimpan perubahan data staf <strong>{{ $staffItem->nama }}</strong>?"
                    data-confirm-btn="<i class='bx bx-save me-1'></i>Ya, Simpan Perubahan">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Edit Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_nip_{{ $staffItem->id }}">NIP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="edit_nip_{{ $staffItem->id }}" name="nip"
                                    value="{{ $staffItem->nip ?? '' }}" placeholder="Contoh: 199108162015031001" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_nama_{{ $staffItem->id }}">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="edit_nama_{{ $staffItem->id }}" name="nama"
                                    value="{{ $staffItem->nama ?? '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_email_{{ $staffItem->id }}">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="edit_email_{{ $staffItem->id }}" name="email"
                                    value="{{ $staffItem->email ?? '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_pwd_{{ $staffItem->id }}">Password Baru</label>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control" id="edit_pwd_{{ $staffItem->id }}" name="new_password"
                                        placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6" autocomplete="new-password" />
                                    <span class="input-group-text cursor-pointer toggle-password" style="cursor: pointer;"><i class="bx bx-hide"></i></span>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.74rem;">Biarkan kosong jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_jabatan_{{ $staffItem->id }}">Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="edit_jabatan_{{ $staffItem->id }}" name="jabatan"
                                    value="{{ $staffItem->jabatan ?? '' }}" placeholder="Contoh: Pengadministrasi Persuratan" />
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
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $('.btn-delete-staff').click(function() {
                const action = $(this).data('action');
                const nama = $(this).data('nama');
                const nip = $(this).data('nip');
                const jabatan = $(this).data('jabatan');

                $('#deleteStaffForm').attr('action', action);
                $('#deleteStaffNama').text(nama);
                $('#deleteStaffNip').text('NIP: ' + nip);
                $('#deleteStaffJabatan').text('Jabatan: ' + jabatan);
            });
        });
    </script>
@endpush
