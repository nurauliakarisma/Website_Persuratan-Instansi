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

    <button type="button" data-bs-toggle="modal" data-bs-target="#addIndexKegiatanModal"
        class="btn btn-warning btn-lg col-12">&plus; Index Kegiatan</button>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Index</th>
                            <th>Keterangan Index</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($indexes as $key => $index)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $index->kode }}</td>
                                <td>{{ $index->keterangan }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" role="button"
                                                href="#editIndexKegiatanModal-{{ $index->id }}" data-bs-toggle="modal">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>
                                            <a class="dropdown-item text-danger btn-delete" role="button"
                                                href="#deleteModal" data-bs-toggle="modal"
                                                data-action="{{ route('master.index-kegiatan.destroy', $index->id) }}">
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
                            <th>No.</th>
                            <th>Nomor Index</th>
                            <th>Keterangan Index</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Index Kegiatam --}}
    <div class="modal fade" id="addIndexKegiatanModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('master.index-kegiatan.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Index Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-2 col-md-3` col-form-label" for="kode">Nomor Index</label>
                        <div class="col-sm-10 col-md-9">
                            <input type="text" class="form-control" id="kode" name="kode" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-md-3` col-form-label" for="keterangan">Keterangan Index</label>
                        <div class="col-sm-10 col-md-9">
                            <input type="text" class="form-control" id="keterangan" name="keterangan" />
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

    {{-- Edit Index Kegiatam --}}
    @foreach ($indexes as $key => $index)
        <div class="modal fade" id="editIndexKegiatanModal-{{ $index->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('master.index-kegiatan.update', $index->id) }}" method="POST" class="modal-content">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Index Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-2 col-md-3` col-form-label" for="kode">Nomor Index</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" id="kode" name="kode"
                                    value="{{ $index->kode }}" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-md-3` col-form-label" for="keterangan">Keterangan Index</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ $index->keterangan }}" />
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
                    <h5 class="modal-title" id="modalCenterTitle">Confirmasi Delete Index Kegiatan</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data Index Kegiatan?</h5>
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
