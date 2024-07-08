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
        class="btn btn-warning btn-lg col-12">&plus; Sub Kegiatan</button>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Kegiatan</th>
                            <th>Sub Kegiatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subs as $key => $sub)
                            <tr>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $sub->kode_program }} <br>
                                        {{ $sub->ket_program }}
                                    </p>
                                </td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $sub->kode_kegiatan }} <br>
                                        {{ $sub->ket_kegiatan }}
                                    </p>
                                </td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $sub->kode_subkegiatan }} <br>
                                        {{ $sub->ket_subkegiatan }}
                                    </p>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" role="button"
                                                href="#editSubKegiatanModal-{{ $sub->id }}" data-bs-toggle="modal">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>
                                            <a class="dropdown-item text-danger btn-delete" role="button"
                                                href="#deleteModal" data-bs-toggle="modal"
                                                data-action="{{ route('master.sub-kegiatan.destroy', $sub->id) }}">
                                                <i class="bx bx-trash me-1"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Program</th>
                            <th>Kegiatan</th>
                            <th>Sub Kegiatan</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Sub Kegiatam --}}
    <div class="modal fade" id="addSubKegiatanModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('master.sub-kegiatan.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Sub Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="program">Program</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="program" name="kode_program"
                                placeholder="Kode" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="ket_program" name="ket_program"
                                placeholder="Keterangan" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="kegiatan">Kegiatan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="kegiatan" name="kode_kegiatan"
                                placeholder="Kode" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="ket_kegiatan" name="ket_kegiatan"
                                placeholder="Keterangan" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="subkegiatan">Sub Kegiatan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="subkegiatan" name="kode_subkegiatan"
                                placeholder="Kode" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="ket_subkegiatan" name="ket_subkegiatan"
                                placeholder="Keterangan" />
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

    {{-- Edit Sub Kegiatam --}}
    @foreach ($subs as $key => $sub)
        <div class="modal fade" id="editSubKegiatanModal-{{ $sub->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('master.sub-kegiatan.update', $sub->id) }}" method="POST" class="modal-content">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Sub Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="program">Program</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="program" name="kode_program"
                                    placeholder="Kode" value="{{ $sub->kode_program }}" disabled />
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="ket_program" name="ket_program"
                                    placeholder="Keterangan" value="{{ $sub->ket_program }}" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="kegiatan">Kegiatan</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="kegiatan" name="kode_kegiatan"
                                    placeholder="Kode" value="{{ $sub->kode_kegiatan }}" disabled />
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="ket_kegiatan" name="ket_kegiatan"
                                    placeholder="Keterangan" value="{{ $sub->ket_kegiatan }}" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-form-label" for="subkegiatan">Sub Kegiatan</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="subkegiatan" name="kode_subkegiatan"
                                    placeholder="Kode" value="{{ $sub->kode_subkegiatan }}" disabled />
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="ket_subkegiatan" name="ket_subkegiatan"
                                    placeholder="Keterangan" value="{{ $sub->kode_subkegiatan }}" />
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

    {{-- Delete Index Kegiatan --}}
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Confirmasi Delete Sub Kegiatan</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data Sub Kegiatan?</h5>
                    <p>Ini akan menghapus seluruh data pengajuan yang terkait.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Tidak
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $('.btn-delete').click(function() {
                const actionUrl = $(this).data('action')
                $('#deleteModal form').attr('action', actionUrl)
            })
        })
    </script>
@endpush
