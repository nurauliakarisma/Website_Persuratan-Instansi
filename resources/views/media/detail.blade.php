@extends('layouts.single')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Publikasi Media</h5>
            {{-- <h6 class="card-subtitle">No. Sub Kegiatan &rightarrow; No. Rincian Belanja</h6> --}}
            
         </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Media</th>
                            <th>Judul Publikasi</th>
                            <th>Tanggal Tayang</th>
                            <th>Nominal Publikasi</th>
                            <th>Nominal Fotocopy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $num => $publikasi)
                            <tr>
                                <td>{{ ++$num }}</td>
                                <td>{{ $publikasi->media->nama }}</td>
                                <td>{{ $publikasi->judul }}</td>
                                <td>{{ $publikasi->tanggal_tayang }}</td>
                                <td>Rp{{ number_format($publikasi->nominal_publikasi, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($publikasi->nominal_fotocopy, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Media</th>
                            <th>Judul Publikasi</th>
                            <th>Tanggal Tayang</th>
                            <th>Nominal Publikasi</th>
                            <th>Nominal Fotocopy</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
