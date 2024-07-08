@extends('layouts.single')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Nomor NPD</h5>
            <h6 class="card-subtitle">No. Sub Kegiatan &rightarrow; No. Rincian Belanja</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rekening Sub Kegiatan</th>
                            <th>Rekening Rincian Belanja</th>
                            <th>Nomor NPD</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Uraian Kegiatan</th>
                            <th>Anggaran</th>
                            <th>Approval</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $npd)
                            <tr>
                                <td>{{ $npd->kode }}</td>
                                <td>{{ $npd->alokasi->subKegiatan->kode_subkegiatan }}</td>
                                <td>{{ $npd->alokasi->rincianBelanja->kode_rekening }}</td>
                                <td>{{ $npd->nomor }}</td>
                                <td>{{ $npd->tanggal_pengajuan }}</td>
                                <td>
                                    <p style="width: 500px; text-align: justify;" class="text-wrap m-0">
                                        {{ $npd->uraian_kegiatan }}
                                    </p>
                                </td>
                                <td>Rp{{ number_format($npd->anggaran, 2, '.', ',') }}</td>
                                <td>
                                    <div class="m-0 d-flex align-items-center justify-content-center gap-2">
                                        @if ($npd->status === 'Diajukan')
                                            @if (request()->user()->tipe === 'Staff')
                                                <span class="badge rounded-pill text-bg-warning">{{ $npd->status }}</span>
                                            @else
                                                <form
                                                    action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}"
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
                                                    action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $npd->id]) }}"
                                                    method="POST">
                                                    @method('put')
                                                    @csrf
                                                    <input type="hidden" name="status" value="Ditolak">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bx bx-x-circle"></i>
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span
                                                class="badge rounded-pill text-bg-{{ $npd->status === 'Disetujui' ? 'success' : 'danger' }}">{{ $npd->status }}</span>
                                        @endif
                                    </div>
                                </td>
                                {{-- <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);"><i
                                                    class="bx bx-show-alt me-1"></i>
                                                Detail</a>
                                            <a class="dropdown-item" href="javascript:void(0);"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                Edit</a>
                                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                                Delete</a>
                                        </div>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Rekening Sub Kegiatan</th>
                            <th>Rekening Rincian Belanja</th>
                            <th>Nomor NPD</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Uraian Kegiatan</th>
                            <th>Anggaran</th>
                            <th>Approval</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
