@php
    $role = auth()->user()->tipe ?? request()->user()->tipe ?? '';
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
        class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Media Baru
    </button>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-broadcast text-primary fs-5"></i> Data Media
            </h6>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('media.show', ['prev_url' => url()->current()]) }}"
                    class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                    <i class="bx bx-show"></i> Lihat Pengajuan
                </a>
                <a href="{{ route('media.export') }}" target="_blank"
                    class="btn btn-sm btn-success d-flex align-items-center gap-1">
                    <i class="bx bx-spreadsheet"></i> Export Excel
                </a>
            </div>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 260px;">Nama Perusahaan Media</th>
                            <th style="min-width: 170px;">Harga Penawaran</th>
                            <th style="min-width: 170px;">Harga Deal (Nett)</th>
                            <th style="min-width: 190px;">Total Kontrak (+ PPN 11%)</th>
                            @if (in_array($role, ['Super Admin', 'Admin A']))
                                <th class="text-center" style="min-width: 140px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medias as $media)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class="bx bx-news"></i>
                                            </span>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">
                                            {{ $media->nama }}
                                        </span>
                                    </div>
                                </td>
                                <td><span class="text-muted fw-semibold">Rp{{ number_format($media->harga_penawaran, 0, ',', '.') }}</span></td>
                                <td><span class="fw-bold text-dark">Rp{{ number_format($media->harga_deal, 0, ',', '.') }}</span></td>
                                <td>
                                    <span class="badge bg-label-primary fs-6 fw-bold px-2 py-1">
                                        Rp{{ number_format($media->harga_total, 0, ',', '.') }}
                                    </span>
                                </td>
                                @if (in_array($role, ['Super Admin', 'Admin A']))
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <a class="btn btn-xs btn-outline-warning px-2 py-1" href="#editStaffModal-{{ $media->id }}"
                                                data-bs-toggle="modal" role="button" title="Edit Data Media">
                                                <i class="bx bx-edit-alt me-1"></i>Edit
                                            </a>
                                            <form action="{{ route('media.destroy', $media->id) }}" method="POST" class="d-inline form-confirm-delete"
                                                data-confirm-title="Konfirmasi Hapus Media"
                                                data-confirm-text="Apakah Anda yakin ingin menghapus media <strong>{{ $media->nama }}</strong>?">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-outline-danger px-2 py-1" title="Hapus Media">
                                                    <i class="bx bx-trash me-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="d-md-none mobile-data-list">
                @forelse ($medias as $media)
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-media">
                        <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                            <div class="fw-bold text-dark fs-6">{{ $media->nama }}</div>
                            <span class="badge bg-label-primary px-2 py-1 flex-shrink-0">
                                Total: Rp{{ number_format($media->harga_total, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="p-2 bg-lighter rounded mb-2" style="font-size: 0.76rem;">
                            <div class="d-flex justify-content-between text-muted mb-1">
                                <span>Penawaran:</span>
                                <strong>Rp{{ number_format($media->harga_penawaran, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span>Harga Deal:</span>
                                <strong class="text-success">Rp{{ number_format($media->harga_deal, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        @if (in_array($role, ['Super Admin', 'Admin A']))
                            <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                                <a class="btn btn-xs btn-outline-warning px-3 py-1 fw-medium d-flex align-items-center"
                                    href="#editStaffModal-{{ $media->id }}" data-bs-toggle="modal" role="button">
                                    <i class="bx bx-edit-alt me-1"></i>Edit
                                </a>
                                <form action="{{ route('media.destroy', $media->id) }}" method="POST" class="d-inline form-confirm-delete"
                                    data-confirm-title="Konfirmasi Hapus Media"
                                    data-confirm-text="Apakah Anda yakin ingin menghapus media <strong>{{ $media->nama }}</strong>?">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-outline-danger px-3 py-1 fw-medium d-flex align-items-center">
                                        <i class="bx bx-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada data media.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal -->
    {{-- Tambah Media --}}
    <div class="modal fade" id="addMediaModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('media.store') }}" method="POST" class="modal-content needs-validation form-confirm"
                novalidate data-confirm-title="Konfirmasi Tambah Media" data-confirm-text="Apakah Anda yakin ingin menambahkan data mitra media baru ini?" data-confirm-btn="<i class='bx bx-plus-circle me-1'></i>Ya, Simpan">
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
                        <label class="col-sm-2 col-form-label" for="status">Status</label>
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
                        class="modal-content needs-validation form-confirm" novalidate
                        data-confirm-title="Konfirmasi Simpan Perubahan"
                        data-confirm-text="Apakah Anda yakin ingin menyimpan perubahan data media <strong>{{ $media->nama }}</strong>?"
                        data-confirm-btn="<i class='bx bx-save me-1'></i>Ya, Simpan Perubahan">
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
