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

    <a href="{{ route('nodin.pengajuan', ['bagian' => $bagian]) }}" class="btn btn-warning btn-lg d-block">&plus; Nota
        Dinas</a>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="card-title">Data NODIN</h6>
                <a href="{{ route('nodin.export', ['bagian' => $bagian]) }}" target="_blank"
                    class="btn btn-sm btn-success">Export to Excel</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nomor Nodin</th>
                            <th>Tanggal Surat</th>
                            <th>Perihal</th>
                            <th>Atas Nama</th>
                            <th>Approval</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nodins as $nodin)
                            <tr>
                                <td>{{ $nodin->nomor }}</td>
                                <td>{{ $nodin->tanggal_pengajuan }}</td>
                                <td>
                                    <p style="width: 500px; text-align: justify;" class="text-wrap m-0">
                                        {{ $nodin->perihal }}
                                    </p>
                                </td>
                                <td>
                                    <p style="text-align: justify;" class="text-wrap m-0">
                                        {{ $nodin->atas_nama }}
                                    </p>
                                </td>
                                <td>
                                    <div class="m-0 d-flex align-items-center justify-content-center gap-2">
                                        @if ($nodin->status === 'Diajukan')
                                            <form
                                                action="{{ route('nodin.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}"
                                                method="POST">
                                                @method('put')
                                                @csrf
                                                <input type="hidden" name="status" value="Disetujui">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bx bx-check-circle"></i>
                                                    Setujui
                                                </button>
                                            </form>
                                            <form
                                                action="{{ route('nodin.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNODIN' => $nodin->id]) }}"
                                                method="POST">
                                                @method('put')
                                                @csrf
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bx bx-x-circle"></i>
                                                    Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span
                                                class="badge rounded-pill text-bg-{{ $nodin->status === 'Disetujui' ? 'success' : 'danger' }}">{{ $nodin->status }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-link" data-bs-toggle="modal"
                                        data-bs-target="#detailModal-{{ $nodin->id }}">
                                        <i class="bx bx-show"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nomor Nodin</th>
                            <th>Tanggal Surat</th>
                            <th>Perihal</th>
                            <th>Atas Nama</th>
                            <th>Approval</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @foreach ($nodins as $nodin)
        <div class="modal fade" id="detailModal-{{ $nodin->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="detailModalLabel">Detail NODIN</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nomorNodin" class="form-label">Nomor</label>
                            <input type="text" readonly class="form-control-plaintext" id="nomorNodin"
                                value="{{ $nodin->nomor }}">
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="text" readonly class="form-control-plaintext" id="tanggal_mulai"
                                    value="{{ $nodin->tanggal_mulai }}">
                            </div>
                            <div class="col-sm-6">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="text" readonly class="form-control-plaintext" id="tanggal_selesai"
                                    value="{{ $nodin->tanggal_selesai }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" readonly class="form-control-plaintext" id="subject"
                                value="{{ $nodin->subject }}">
                        </div>
                        <div class="mb-3">
                            <label for="perihal" class="form-label">Perihal</label>
                            <textarea readonly class="form-control-plaintext" id="perihal">{{ $nodin->perihal }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="atas_nama" class="form-label">Atas Nama</label>
                            <input type="text" readonly class="form-control-plaintext" id="atas_nama"
                                value="{{ $nodin->atas_nama }}">
                        </div>
                        <div class="mb-3">
                            <label for="nama_penginput" class="form-label">Penginput</label>
                            <input type="text" readonly class="form-control-plaintext" id="nama_penginput"
                                value="{{ $nodin->nama_penginput }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
