@php
    $role = request()->user()->tipe ?? '';
@endphp
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

    <button type="button" data-bs-toggle="modal" data-bs-target="#addMediaModal"
        class="btn btn-warning btn-lg col-12">&plus; Tambah Media</button>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="card-title">Data Media</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('media.show', ['prev_url' => url()->current()]) }}"
                        class="btn btn-sm btn-primary">Lihat Pengajuan</a>
                    <a href="{{ route('media.export') }}" target="_blank" class="btn btn-sm btn-success">Export to Excel</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga Penawaran</th>
                            <th>Harga Deal</th>
                            <th>Harga + PPN</th>
                            @if (in_array($role, ['Super Admin', 'Admin A']))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medias as $media)
                            <tr>
                                <td>{{ $media->nama }}</td>
                                <td>Rp{{ number_format($media->harga_penawaran, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($media->harga_deal, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($media->harga_total, 2, ',', '.') }}</td>
                                @if (in_array($role, ['Super Admin', 'Admin A']))
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#editStaffModal-{{ $media->id }}"
                                                    data-bs-toggle="modal" role="button"><i
                                                        class="bx bx-edit-alt me-1"></i>
                                                    Edit</a>
                                                <form action="{{ route('media.destroy', $media->id) }}" method="POST">
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
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Harga Penawaran</th>
                            <th>Harga Deal</th>
                            <th>Harga + PPN</th>
                            @if (in_array($role, ['Super Admin', 'Admin A']))
                                <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Media --}}
    <div class="modal fade" id="addMediaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('media.store') }}" method="POST" class="modal-content needs-validation" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Form Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="nama">Nama Media</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="harga_penawaran">Harga Penawaran</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="harga_penawaran" name="harga_penawaran" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="harga_deal">Harga Deal</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="harga_deal" name="harga_deal" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="harga_total">Harga + PPN</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="harga_total" name="harga_total" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="status">Keterangan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="status" name="status" />
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

    @if (in_array($role, ['Super Admin', 'Admin A']))
        {{-- Edit Media --}}
        @foreach ($medias as $media)
            <div class="modal fade" id="editStaffModal-{{ $media->id }}" data-bs-backdrop="static" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form action="{{ route('media.update', $media->id) }}" method="POST"
                        class="modal-content needs-validation" novalidate>
                        @method('put')
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Form Media</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="nama">Nama Media</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="{{ $media->nama }}" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="harga_penawaran">Harga Penawaran</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="harga_penawaran"
                                        name="harga_penawaran" value="{{ $media->harga_penawaran }}" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="harga_deal">Harga Deal</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="harga_deal" name="harga_deal"
                                        value="{{ $media->harga_deal }}" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="harga_total">Harga + PPN</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="harga_total" name="harga_total"
                                        value="{{ $media->harga_total }}" />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="status">Keterangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="status" name="status"
                                        value="{{ $media->status }}" />
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
    @endif
@endsection
