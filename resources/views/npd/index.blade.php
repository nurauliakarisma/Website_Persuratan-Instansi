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

    <a href="{{ route('npd.tambah', ['bagian' => $bagian]) }}" class="btn btn-warning w-100 shadow-sm py-2 px-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px;">
        <i class="bx bx-plus-circle fs-5"></i> Tambah Kode Rekening
    </a>

    <div class="card mt-3 shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bx bx-file text-primary fs-5"></i> Data NPD
            </h6>
            <a href="{{ route('npd.export', ['bagian' => $bagian]) }}" target="_blank"
                class="btn btn-sm btn-success d-flex align-items-center gap-1">
                <i class="bx bx-spreadsheet"></i> Export Excel
            </a>
        </div>
        <div class="card-body p-2 p-sm-4">
            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle mb-0" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 28%;">Sub Kegiatan & Program</th>
                            <th style="width: 24%;">Rekening Belanja</th>
                            <th style="width: 15%;">Pagu Anggaran</th>
                            <th style="width: 19%;">Realisasi & Sisa</th>
                            <th class="text-center" style="width: 14%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alokasi_npd as $key => $alokasi)
                            @php
                                $persen = $alokasi->total_anggaran > 0 
                                    ? round(($alokasi->realisasi / $alokasi->total_anggaran) * 100, 2) 
                                    : 0;
                                $sisa = max(0, $alokasi->total_anggaran - ($alokasi->realisasi ?? 0));
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-secondary font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.74rem;">
                                            {{ $alokasi->subKegiatan->kode_subkegiatan }}
                                        </span>
                                        <span class="fw-bold text-dark" style="font-size: 0.86rem; line-height: 1.35;">
                                            {{ $alokasi->subKegiatan->ket_subkegiatan }}
                                        </span>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.74rem;">
                                            <i class="bx bx-subdirectory-right text-primary me-1"></i><strong>Kegiatan:</strong> {{ $alokasi->subKegiatan->ket_kegiatan }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-label-primary font-monospace px-2 py-1 mb-1 align-self-start" style="font-size: 0.74rem;">
                                            {{ $alokasi->rincianBelanja->kode_rekening }}
                                        </span>
                                        <span class="fw-semibold text-dark" style="font-size: 0.84rem; line-height: 1.35;">
                                            {{ $alokasi->rincianBelanja->keterangan }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark" style="font-size: 0.92rem;">
                                        Rp{{ number_format($alokasi->total_anggaran, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-success" style="font-size: 0.84rem;">
                                                Rp{{ number_format($alokasi->realisasi ?? 0, 0, ',', '.') }}
                                            </span>
                                            <span class="badge {{ $persen >= 100 ? 'bg-label-danger' : ($persen > 0 ? 'bg-label-success' : 'bg-label-secondary') }} py-0 px-1" style="font-size: 0.7rem;">
                                                {{ number_format($persen, 1, ',', '.') }}%
                                            </span>
                                        </div>
                                        <div class="progress mb-1" style="height: 5px; border-radius: 4px;">
                                            <div class="progress-bar {{ $persen >= 100 ? 'bg-danger' : ($persen > 50 ? 'bg-primary' : 'bg-success') }}" 
                                                role="progressbar" 
                                                style="width: {{ min(100, $persen) }}%" 
                                                aria-valuenow="{{ $persen }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 0.74rem;">
                                            <span class="text-muted">Sisa Pagu:</span>
                                            <span class="fw-bold text-info">Rp{{ number_format($sisa, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <a class="btn btn-xs btn-primary px-2 py-1"
                                            href="{{ route('npd.detail', ['bagian' => $bagian, 'alokasi' => $alokasi->id, 'prev_url' => $current_url]) }}"
                                            title="Lihat Detail">
                                            <i class="bx bx-show-alt me-1"></i>Detail
                                        </a>
                                        <a class="btn btn-xs btn-outline-warning px-2 py-1"
                                            href="{{ route('npd.edit', ['bagian' => $bagian, 'alokasiNPD' => $alokasi->id]) }}"
                                            title="Edit Pagu">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1 btn-delete"
                                            href="#deleteModal" data-bs-toggle="modal"
                                            data-action="{{ route('npd.delete.action', ['bagian' => $bagian, 'alokasiNPD' => $alokasi->id]) }}"
                                            title="Hapus Rekening">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="d-md-none mobile-data-list">
                @forelse ($alokasi_npd as $alokasi)
                    @php
                        $persen = $alokasi->total_anggaran > 0 
                            ? round(($alokasi->realisasi / $alokasi->total_anggaran) * 100, 2) 
                            : 0;
                    @endphp
                    <div class="p-3 mb-2 bg-white rounded-3 border shadow-xs card-item-npd"
                        style="cursor: pointer;"
                        onclick="window.location='{{ route('npd.detail', ['bagian' => $bagian, 'alokasi' => $alokasi->id, 'prev_url' => $current_url]) }}'">
                        <div class="d-flex align-items-start justify-content-between mb-1 gap-2">
                            <div>
                                <span class="badge bg-label-primary fw-semibold mb-1">{{ $alokasi->rincianBelanja->kode_rekening ?? '-' }}</span>
                                <div class="fw-bold text-dark fs-6">{{ $alokasi->rincianBelanja->keterangan ?? '-' }}</div>
                            </div>
                            <span class="badge {{ $persen >= 100 ? 'bg-label-danger' : ($persen > 0 ? 'bg-label-success' : 'bg-label-secondary') }} px-2 py-1 flex-shrink-0">
                                {{ number_format($persen, 2, ',', '.') }}%
                            </span>
                        </div>
                        
                        <div class="p-2 bg-lighter rounded mb-2" style="font-size: 0.76rem;">
                            <div class="text-muted mb-1">
                                <i class="bx bx-list-check me-1 text-primary"></i><strong>Sub Kegiatan:</strong> {{ $alokasi->subKegiatan->kode_subkegiatan ?? '-' }} - {{ $alokasi->subKegiatan->ket_subkegiatan ?? '-' }}
                            </div>
                            <div class="d-flex justify-content-between text-dark pt-1 border-top mt-1">
                                <div><small class="text-muted">Pagu Anggaran:</small><br><strong>Rp{{ number_format($alokasi->total_anggaran, 0, ',', '.') }}</strong></div>
                                <div class="text-end"><small class="text-muted">Realisasi:</small><br><strong class="text-primary">Rp{{ number_format($alokasi->realisasi ?? 0, 0, ',', '.') }}</strong></div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top" onclick="event.stopPropagation();">
                            <a class="btn btn-xs btn-outline-primary px-3 py-1 fw-medium d-flex align-items-center"
                                href="{{ route('npd.detail', ['bagian' => $bagian, 'alokasi' => $alokasi->id, 'prev_url' => $current_url]) }}">
                                <i class="bx bx-show-alt me-1"></i>Detail
                            </a>
                            <a class="btn btn-xs btn-outline-warning px-3 py-1 fw-medium d-flex align-items-center"
                                href="{{ route('npd.edit', ['bagian' => $bagian, 'alokasiNPD' => $alokasi->id]) }}">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                            </a>
                            <button type="button" class="btn btn-xs btn-outline-danger px-3 py-1 fw-medium d-flex align-items-center btn-delete"
                                href="#deleteModal" data-bs-toggle="modal"
                                data-action="{{ route('npd.delete.action', ['bagian' => $bagian, 'alokasiNPD' => $alokasi->id]) }}">
                                <i class="bx bx-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-3 d-block mb-1"></i>
                        Belum ada data alokasi NPD.
                    </div>
                @endforelse
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
