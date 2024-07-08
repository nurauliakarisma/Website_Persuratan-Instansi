@extends('layouts.single')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Nomor NODIN</h5>
            {{-- <h6 class="card-subtitle">No. Sub Kegiatan &rightarrow; No. Rincian Belanja</h6> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rekening Index Kegiatan</th>
                            <th>Rekening Sub Kegiatan</th>
                            <th>Rekening Rincian Belanja</th>
                            <th>Nomor NODIN</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Subject</th>
                            <th>Perihal</th>
                            <th>Atas Nama</th>
                            <th>Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $nodin)
                            <tr>
                                <td>{{ $nodin->kode }}</td>
                                <td>{{ $nodin->indexKegiatan->kode }}</td>
                                <td>{{ $nodin->subKegiatan->kode_subkegiatan }}</td>
                                <td>{{ $nodin->rincianBelanja->kode_rekening }}</td>
                                <td>{{ $nodin->nomor }}</td>
                                <td>{{ $nodin->tanggal_mulai }}</td>
                                <td>{{ $nodin->tanggal_selesai }}</td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $nodin->subject }}
                                    </p>
                                </td>
                                <td>
                                    <p style="width: 500px; text-align: justify;" class="text-wrap m-0">
                                        {{ $nodin->perihal }}
                                    </p>
                                </td>
                                <td>{{ $nodin->atas_nama }}</td>
                                <td>
                                    <div class="m-0 d-flex align-items-center justify-content-center gap-2">
                                        @if ($nodin->status === 'Diajukan')
                                            @if (request()->user()->tipe === 'Staff')
                                                <span
                                                    class="badge rounded-pill text-bg-warning">{{ $nodin->status }}</span>
                                            @else
                                                <form
                                                    action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $nodin->id]) }}"
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
                                                    action="{{ route('npd.pengajuan.approve', ['bagian' => $bagian, 'pengajuanNPD' => $nodin->id]) }}"
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
                                                class="badge rounded-pill text-bg-{{ $nodin->status === 'Disetujui' ? 'success' : 'danger' }}">{{ $nodin->status }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Rekening Index Kegiatan</th>
                            <th>Rekening Sub Kegiatan</th>
                            <th>Rekening Rincian Belanja</th>
                            <th>Nomor NODIN</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Subject</th>
                            <th>Perihal</th>
                            <th>Atas Nama</th>
                            <th>Approval</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
