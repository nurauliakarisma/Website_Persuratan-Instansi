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

    <button type="button" data-bs-toggle="modal" data-bs-target="#addAdminModal"
        class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Admin Baru
    </button>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-shield-quarter text-primary fs-5"></i> Data Admin
            </h6>
            <span class="badge bg-label-primary rounded-pill px-2 py-1">{{ $admins->count() }} Admin</span>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td><strong>{{ $admin->nama }}</strong></td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if ($admin->tipe === 'Super Admin')
                                        <span class="badge bg-label-primary fw-semibold"><i class="bx bx-shield-quarter me-1"></i>Super Admin</span>
                                    @elseif ($admin->tipe === 'Admin A')
                                        <span class="badge bg-label-info fw-semibold"><i class="bx bx-news me-1"></i>Admin Dokinfo</span>
                                    @elseif ($admin->tipe === 'Admin B')
                                        <span class="badge bg-label-warning fw-semibold"><i class="bx bx-briefcase-alt-2 me-1"></i>Admin FPP</span>
                                    @else
                                        <span class="badge bg-label-secondary fw-semibold">{{ $admin->tipe }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <a class="btn btn-xs btn-outline-warning" href="#editAdminModal-{{ $admin->id }}"
                                            data-bs-toggle="modal" role="button">
                                            <i class="bx bx-edit-alt me-1"></i>Edit
                                        </a>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-delete-admin"
                                            data-bs-toggle="modal" data-bs-target="#deleteAdminModal"
                                            data-action="{{ route('user.admin.hapus', $admin->id) }}"
                                            data-nama="{{ $admin->nama }}"
                                            data-role="{{ $admin->tipe === 'Admin A' ? 'Admin Dokinfo' : ($admin->tipe === 'Admin B' ? 'Admin FPP' : $admin->tipe) }}">
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
                @forelse ($admins as $admin)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                            <div>
                                <div class="fw-bold text-dark fs-6">{{ $admin->nama }}</div>
                                <small class="text-muted d-flex align-items-center gap-1 mt-1" style="font-size: 0.78rem;">
                                    <i class="bx bx-envelope text-secondary"></i> {{ $admin->email }}
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                @if ($admin->tipe === 'Super Admin')
                                    <span class="badge bg-label-primary fw-semibold"><i class="bx bx-shield-quarter me-1"></i>Super Admin</span>
                                @elseif ($admin->tipe === 'Admin A')
                                    <span class="badge bg-label-info fw-semibold"><i class="bx bx-news me-1"></i>Dokinfo</span>
                                @elseif ($admin->tipe === 'Admin B')
                                    <span class="badge bg-label-warning fw-semibold"><i class="bx bx-briefcase-alt-2 me-1"></i>FPP</span>
                                @else
                                    <span class="badge bg-label-secondary fw-semibold">{{ $admin->tipe }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                            <a class="btn btn-xs btn-outline-warning px-3 py-1 fw-medium d-flex align-items-center"
                                href="#editAdminModal-{{ $admin->id }}" data-bs-toggle="modal">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 fw-medium d-flex align-items-center btn-delete-admin"
                                data-bs-toggle="modal" data-bs-target="#deleteAdminModal"
                                data-action="{{ route('user.admin.hapus', $admin->id) }}"
                                data-nama="{{ $admin->nama }}"
                                data-role="{{ $admin->tipe === 'Admin A' ? 'Admin Dokinfo' : ($admin->tipe === 'Admin B' ? 'Admin FPP' : $admin->tipe) }}">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada data admin.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Admin -->
    <div class="modal fade" id="deleteAdminModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="deleteAdminForm" action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center text-danger">
                        <i class="bx bx-trash fs-4 me-2"></i>
                        Konfirmasi Hapus Admin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="mb-2 fs-6 text-dark">Apakah Anda yakin ingin menghapus data admin berikut?</p>
                    <div class="p-3 rounded bg-lighter border mb-2">
                        <div class="fw-bold text-dark fs-6" id="deleteAdminNama">-</div>
                        <small class="text-muted d-block" id="deleteAdminRole">-</small>
                    </div>
                    <small class="text-danger"><i class="bx bx-error-circle me-1"></i>Tindakan ini tidak dapat dibatalkan.</small>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>Ya, Hapus Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Admin --}}
    <div class="modal fade" id="addAdminModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('user.admin.tambah') }}" method="POST" class="modal-content form-confirm"
                enctype="multipart/form-data" data-confirm-title="Konfirmasi Tambah Admin" data-confirm-text="Apakah Anda yakin ingin menambahkan admin baru ini?" data-confirm-btn="<i class='bx bx-plus-circle me-1'></i>Ya, Tambah Admin">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_admin_nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_admin_nama" name="nama" required placeholder="Contoh: Admin Persuratan" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_admin_email">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="add_admin_email" name="email" required placeholder="admin@dprd.jatimprov.go.id" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_admin_password">Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" class="form-control" id="add_admin_password" name="password" required minlength="6" placeholder="Minimal 6 karakter" autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer toggle-password" style="cursor: pointer;"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_admin_tipe">Role / Tipe <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="tipe" id="add_admin_tipe" class="form-select" required>
                                <option value="" disabled selected>Pilih Tipe Admin</option>
                                <option value="Super Admin">Super Admin</option>
                                <option value="Admin A">Admin Dokinfo</option>
                                <option value="Admin B">Admin FPP</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="add_admin_photo">Foto Profil</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="add_admin_photo" name="photo" accept="image/png,image/jpeg,image/jpg" />
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

    {{-- Edit Admin --}}
    @foreach ($admins as $admin)
        <div class="modal fade" id="editAdminModal-{{ $admin->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('user.admin.ubah', ['user' => $admin->id]) }}" method="POST"
                    class="modal-content form-confirm" enctype="multipart/form-data"
                    data-confirm-title="Konfirmasi Simpan Perubahan"
                    data-confirm-text="Apakah Anda yakin ingin menyimpan perubahan data admin <strong>{{ $admin->nama }}</strong>?"
                    data-confirm-btn="<i class='bx bx-save me-1'></i>Ya, Simpan Perubahan">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Edit Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_admin_nama_{{ $admin->id }}">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="edit_admin_nama_{{ $admin->id }}" name="nama"
                                    value="{{ $admin->nama ?? '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_admin_email_{{ $admin->id }}">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="edit_admin_email_{{ $admin->id }}" name="email"
                                    value="{{ $admin->email ?? '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_admin_pwd_{{ $admin->id }}">Password Baru</label>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control" id="edit_admin_pwd_{{ $admin->id }}" name="new_password"
                                        placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6" autocomplete="new-password" />
                                    <span class="input-group-text cursor-pointer toggle-password" style="cursor: pointer;"><i class="bx bx-hide"></i></span>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.74rem;">Biarkan kosong jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_admin_tipe_{{ $admin->id }}">Role / Tipe <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select name="tipe" id="edit_admin_tipe_{{ $admin->id }}" class="form-select" required>
                                    <option value="Super Admin" @selected($admin->tipe == 'Super Admin')>Super Admin</option>
                                    <option value="Admin A" @selected($admin->tipe == 'Admin A')>Admin Dokinfo</option>
                                    <option value="Admin B" @selected($admin->tipe == 'Admin B')>Admin FPP</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="edit_admin_photo_{{ $admin->id }}">Foto Profil</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="edit_admin_photo_{{ $admin->id }}" name="photo" accept="image/png,image/jpeg,image/jpg" />
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
            $('.btn-delete-admin').click(function() {
                const action = $(this).data('action');
                const nama = $(this).data('nama');
                const role = $(this).data('role');

                $('#deleteAdminForm').attr('action', action);
                $('#deleteAdminNama').text(nama);
                $('#deleteAdminRole').text('Role: ' + role);
            });
        });
    </script>
@endpush
