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

    <button type="button" data-bs-toggle="modal" data-bs-target="#addAdminModal"
        class="btn btn-warning btn-lg col-12">&plus; Tambah Admin</button>

    <div class="card mt-4">
        <div class="card-header">
            <h6 class="card-title">Data Admin</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
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
                                <td>{{ $admin->nama }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->tipe }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#editAdminModal-{{ $admin->id }}"
                                                data-bs-toggle="modal" role="button"><i class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <form action="{{ route('user.admin.hapus', $admin->id) }}" method="POST">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bx bx-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Admin --}}
    <div class="modal fade" id="addAdminModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('user.admin.tambah') }}" method="POST" class="modal-content needs-validation"
                enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="nama">Nama Lengkap</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="email">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" name="email" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="password">Buat Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="tipe">Tipe</label>
                        <div class="col-sm-10">
                            <select name="tipe" id="tipe" class="form-select">
                                <option disabled selected>Pilih Tipe Admin</option>
                                <option value="Super Admin">Super Admin</option>
                                <option value="Admin A">Admin Bagian Dokinfo</option>
                                <option value="Admin B">Admin Bagian FPP</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="photo">Photo Profile</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="photo" name="photo" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
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
                    class="modal-content needs-validation" enctype="multipart/form-data" novalidate>
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="nama">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama"
                                    value="{{ $admin->nama ?? '' }}" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="email">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email"
                                    value="{{ $admin->email ?? '' }}" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="password">Password Baru</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="new_password"
                                    placeholder="Isi jika ingin mengganti password" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="tipe">Tipe</label>
                            <div class="col-sm-10">
                                <select name="tipe" class="form-select">
                                    <option disabled selected>Pilih Tipe Admin</option>
                                    <option value="Super Admin" @selected($admin->tipe == 'Super Admin')>Super Admin</option>
                                    <option value="Admin A" @selected($admin->tipe == 'Admin A')>Admin Bagian Dokinfo</option>
                                    <option value="Admin B" @selected($admin->tipe == 'Admin B')>Admin Bagian FPP</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="photo">Photo Profile</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="photo" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
