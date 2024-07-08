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
        class="btn btn-warning btn-lg col-12">&plus; Rincian Belanja</button>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Rekening</th>
                            <th>Rincian Belanja</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rincians as $key => $rincian)
                            <tr>
                                <td>
                                    {{ $rincian->kode_rekening }} <br>
                                </td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $rincian->keterangan }}
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
                                                href="#editRincianBelanjaModal-{{ $rincian->id }}" data-bs-toggle="modal">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>
                                            <a class="dropdown-item text-danger btn-delete" role="button"
                                                href="#deleteModal" data-bs-toggle="modal"
                                                data-action="{{ route('master.rincian-belanja.destroy', $rincian->id) }}">
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
                            <th>Kode Rekening</th>
                            <th>Rincian Belanja</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Rincian Belanja --}}
    <div class="modal fade" id="addRincianBelanjaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('master.rincian-belanja.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Rincian Belanja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="kode_rekening">Kode Rekening</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kode_rekening" name="kode_rekening" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label" for="keterangan">Rincian Belanja</label>
                        <div class="col-sm-9">
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
    @foreach ($rincians as $key => $rincian)
        <div class="modal fade" id="editRincianBelanjaModal-{{ $rincian->id }}" data-bs-backdrop="static" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('master.rincian-belanja.update', $rincian->id) }}" method="POST"
                    class="modal-content">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Edit Rincian Belanja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-2 col-md-3` col-form-label" for="kode">Nomor Index</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" id="kode" name="kode"
                                    value="{{ $rincian->kode_rekening }}" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-2 col-md-3` col-form-label" for="keterangan">Keterangan Index</label>
                            <div class="col-sm-10 col-md-9">
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="{{ $rincian->keterangan }}" />
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

    {{-- Delete Rincian Belanja --}}
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Confirmasi Delete Rincian Belanja</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data rincian belanja?</h5>
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
