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

    <a href="{{ route('npd.tambah', ['bagian' => $bagian]) }}" class="btn btn-warning btn-lg d-block">&plus; Kode
        Rekening</a>

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="card-title">Data NPD</h6>
                <a href="{{ route('npd.export', ['bagian' => $bagian]) }}" target="_blank"
                    class="btn btn-sm btn-success">Export to Excel</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Kegiatan</th>
                            <th>Sub Kegiatan</th>
                            <th>Rincian Belanja</th>
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>Presentase Serapan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alokasi_npd as $key => $alokasi)
                            <tr>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $alokasi->subKegiatan->kode_program }} <br>
                                        {{ $alokasi->subKegiatan->ket_program }}
                                    </p>
                                </td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $alokasi->subKegiatan->kode_kegiatan }} <br>
                                        {{ $alokasi->subKegiatan->ket_kegiatan }}
                                    </p>
                                </td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $alokasi->subKegiatan->kode_subkegiatan }} <br>
                                        {{ $alokasi->subKegiatan->ket_subkegiatan }}
                                    </p>
                                </td>
                                <td>
                                    <p style="width: 300px; text-align: justify;" class="text-wrap m-0">
                                        {{ $alokasi->rincianBelanja->kode_rekening }} <br>
                                        {{ $alokasi->rincianBelanja->keterangan }}
                                    </p>
                                </td>
                                <td>Rp{{ number_format($alokasi->total_anggaran, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($alokasi->realisasi, 2, ',', '.') }}</td>
                                <td>
                                    @php
                                        echo ($alokasi->realisasi / $alokasi->total_anggaran) * 100 . '%';
                                    @endphp
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('npd.detail', ['bagian' => $bagian, 'alokasi' => $alokasi->id, 'prev_url' => $current_url]) }}">
                                                <i class="bx bx-show-alt me-1"></i>
                                                Detail
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('npd.edit', ['bagian' => $bagian, 'alokasiNPD' => $alokasi->id]) }}">
                                                <i class="bx bx-edit-alt me-1"></i>
                                                Edit
                                            </a>
                                            <a class="dropdown-item text-danger btn-delete" role="button"
                                                href="#deleteModal" data-bs-toggle="modal"
                                                data-action="{{ route('npd.delete.action', ['bagian' => $bagian, 'alokasiNPD' => $alokasi->id]) }}">
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
                            <th>Rincian Belanja</th>
                            <th>Anggaran</th>
                            <th>Realisasi</th>
                            <th>Presentase Serapan</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="" method="POST" class="modal-content">
                @method('delete')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Confirmasi Delete NPD</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda yakin ingin menghapus data NPD?</h5>
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
