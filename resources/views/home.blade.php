@extends('layouts.admin')

@push('page-css')
<style>
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(67, 89, 113, 0.1);
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(67, 89, 113, 0.12);
    }
    .chart-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(67, 89, 113, 0.1);
        border-radius: 0.75rem;
    }
    .chart-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(67, 89, 113, 0.12);
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .text-truncate-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bg-lighter {
        background-color: #f8f9fa;
    }
    .stat-pill {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid rgba(67, 89, 113, 0.06);
    }
    .apexcharts-tooltip {
        box-shadow: 0 6px 18px rgba(0,0,0,0.18) !important;
        border-radius: 8px !important;
        border: none !important;
    }
</style>
@endpush

@push('page-js')
    <script>
        $(document).ready(function() {
            let cardColor, headingColor, axisColor, borderColor;

            cardColor = config.colors.white || '#ffffff';
            headingColor = config.colors.headingColor || '#566a7f';
            axisColor = config.colors.axisColor || '#a1acb8';
            borderColor = config.colors.borderColor || '#eceef1';

            // Helper format persentase max 2 angka di belakang koma & dibulatkan
            function formatPercent(val) {
                const num = Number(val);
                if (isNaN(num)) return '0%';
                // Round to max 2 decimals, hapus trailing .00 jika pas bulat
                const rounded = Math.round(num * 100) / 100;
                return rounded.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }) + '%';
            }

            // Helper format Rupiah
            function formatRupiah(num) {
                const val = Number(num);
                if (isNaN(val)) return 'Rp 0';
                return 'Rp ' + val.toLocaleString('id-ID');
            }

            // Inisialisasi tooltip bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            $('.realisasiChart').each(function() {
                const chartData = $(this).data('chart');

                // Rounding data series to max 2 decimal places
                const realisasiPct = Math.round(Number(chartData.realisasiPersen) * 100) / 100;
                const sisaPct = Math.round(Number(chartData.sisaPersen) * 100) / 100;

                // Tentukan warna dinamis berdasarkan serapan
                let primaryColor = '#696cff'; // default purple-blue
                if (realisasiPct >= 90) {
                    primaryColor = '#ff3e1d'; // Danger jika hampir 100% / habis
                } else if (realisasiPct >= 60) {
                    primaryColor = '#ffab00'; // Warning
                } else if (realisasiPct > 0) {
                    primaryColor = '#71dd37'; // Green
                }

                const sisaColor = '#e7e9ed'; // Soft light slate

                new ApexCharts(this, {
                    chart: {
                        type: 'donut',
                        height: 230,
                        parentHeightOffset: 0,
                        toolbar: { show: false }
                    },
                    labels: ['Dana Terserap (Realisasi)', 'Sisa Anggaran'],
                    series: [realisasiPct, sisaPct],
                    colors: [primaryColor, sisaColor],
                    stroke: {
                        width: 4,
                        colors: [cardColor]
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 0,
                            right: 0
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '74%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '1.45rem',
                                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                                        fontWeight: 700,
                                        color: headingColor,
                                        offsetY: -10,
                                        formatter: function(val) {
                                            return formatPercent(chartData.realisasiPersen);
                                        }
                                    },
                                    name: {
                                        offsetY: 20,
                                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                                        fontSize: '0.82rem',
                                        color: axisColor,
                                        formatter: function() {
                                            return 'Dana Terserap';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        fontSize: '0.82rem',
                                        fontFamily: 'Plus Jakarta Sans, sans-serif',
                                        fontWeight: 600,
                                        color: axisColor,
                                        label: 'Dana Terserap',
                                        formatter: function(w) {
                                            return formatPercent(chartData.realisasiPersen);
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        theme: 'dark',
                        fillSeriesColor: false,
                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                            const isRealisasi = seriesIndex === 0;
                            const percentVal = isRealisasi ? chartData.realisasiPersen : chartData.sisaPersen;
                            const nominalVal = isRealisasi ? chartData.realisasiNominal : chartData.sisaNominal;
                            const title = isRealisasi ? 'Dana Terserap (Realisasi)' : 'Sisa Anggaran';
                            const badgeColor = isRealisasi ? 'background-color: #696cff;' : 'background-color: #8592a3;';
                            const indicatorDot = isRealisasi ? primaryColor : '#8592a3';

                            return `
                                <div style="padding: 10px 14px; background: #233446; color: #fff; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; min-width: 190px; line-height: 1.5;">
                                    <div style="display: flex; align-items: center; margin-bottom: 6px;">
                                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: ${indicatorDot}; margin-right: 7px;"></span>
                                        <strong style="color: #fff; font-size: 12px;">${title}</strong>
                                    </div>
                                    <div style="font-size: 14px; font-weight: 700; color: #71dd37; margin-bottom: 4px;">
                                        ${formatRupiah(nominalVal)}
                                    </div>
                                    <div style="color: #b4bdc6; font-size: 11.5px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 5px; margin-top: 5px;">
                                        Persentase: <span style="font-weight: 600; color: #fff; padding: 2px 6px; border-radius: 4px; ${badgeColor}">${formatPercent(percentVal)}</span>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }).render();
            });
        });
    </script>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Row 1: Summary Overview Cards -->
    <div class="row mb-4">
        <!-- Card 1: Total Anggaran -->
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card metric-card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary p-2">
                                <i class="bx bx-wallet fs-4"></i>
                            </span>
                        </div>
                        <span class="badge bg-label-primary rounded-pill">Total Pagu</span>
                    </div>
                    <span class="d-block text-muted small fw-medium">Total Alokasi Anggaran</span>
                    <h4 class="card-title mb-1 text-primary fw-bold">Rp{{ number_format($totalAnggaran, 0, ',', '.') }}</h4>
                    <small class="text-muted"><i class="bx bx-layer me-1"></i>Dari {{ $npds->count() }} Agenda Kegiatan</small>
                </div>
            </div>
        </div>

        <!-- Card 2: Realisasi / Serapan -->
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card metric-card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success p-2">
                                <i class="bx bx-trending-up fs-4"></i>
                            </span>
                        </div>
                        <span class="badge bg-label-success rounded-pill">{{ number_format($persenRealisasiTotal, 2, ',', '.') }}% Terserap</span>
                    </div>
                    <span class="d-block text-muted small fw-medium">Total Dana Terserap (Realisasi)</span>
                    <h4 class="card-title mb-1 text-success fw-bold">Rp{{ number_format($totalRealisasi, 0, ',', '.') }}</h4>
                    <small class="text-success"><i class="bx bx-check-double me-1"></i>Status Disetujui</small>
                </div>
            </div>
        </div>

        <!-- Card 3: Sisa Anggaran -->
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card metric-card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-info p-2">
                                <i class="bx bx-pie-chart-alt fs-4"></i>
                            </span>
                        </div>
                        <span class="badge bg-label-info rounded-pill">{{ number_format($persenSisaTotal, 2, ',', '.') }}% Sisa</span>
                    </div>
                    <span class="d-block text-muted small fw-medium">Total Sisa Anggaran</span>
                    <h4 class="card-title mb-1 text-info fw-bold">Rp{{ number_format($totalSisa, 0, ',', '.') }}</h4>
                    <small class="text-muted"><i class="bx bx-calendar me-1"></i>Tahun Anggaran {{ date('Y') }}</small>
                </div>
            </div>
        </div>

        <!-- Card 4: Agenda Kegiatan -->
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card metric-card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning p-2">
                                <i class="bx bx-file-blank fs-4"></i>
                            </span>
                        </div>
                        <span class="badge bg-label-warning rounded-pill">Aktif</span>
                    </div>
                    <span class="d-block text-muted small fw-medium">Jumlah Agenda Kegiatan</span>
                    <h4 class="card-title mb-1 text-warning fw-bold">{{ $npds->count() }} Agenda</h4>
                    <small class="text-muted"><i class="bx bx-buildings me-1"></i>DPRD Prov. Jatim</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="mb-0 fw-bold text-dark"><i class="bx bx-doughnut-chart me-2 text-primary"></i>Monitoring Penyerapan Anggaran Per Agenda Kegiatan</h5>
            <small class="text-muted">Arahkan kursor pada diagram untuk melihat rincian nominal dan persentase serapan dana.</small>
        </div>
    </div>

    <!-- Grid of Agenda Kegiatan Cards -->
    <div class="row">
        @forelse ($npds as $npd)
            @php
                $realisasiNominal = (float) ($npd->realisasi ?? 0);
                $totalNominal = (float) $npd->total_anggaran;
                $sisaNominal = max(0, $totalNominal - $realisasiNominal);
                $realisasiPersen = $totalNominal > 0 ? round(($realisasiNominal / $totalNominal) * 100, 2) : 0;
                $sisaPersen = $totalNominal > 0 ? round(($sisaNominal / $totalNominal) * 100, 2) : 100;

                // Pastikan sisa persen melengkapi 100%
                if ($realisasiPersen + $sisaPersen != 100 && $totalNominal > 0) {
                    $sisaPersen = round(100 - $realisasiPersen, 2);
                }

                $chartPayload = [
                    'realisasiPersen' => $realisasiPersen,
                    'sisaPersen' => $sisaPersen,
                    'realisasiNominal' => $realisasiNominal,
                    'sisaNominal' => $sisaNominal,
                    'totalNominal' => $totalNominal,
                    'kodeSub' => $npd->subKegiatan->kode_subkegiatan ?? '-',
                    'ketSub' => $npd->subKegiatan->ket_subkegiatan ?? '-',
                    'kodeRincian' => $npd->rincianBelanja->kode_rekening ?? '-',
                    'ketRincian' => $npd->rincianBelanja->keterangan ?? '-',
                    'bagian' => $npd->bagian ?? 'A',
                ];

                $isBagianA = ($npd->bagian === 'A');
                $namaBagian = $isBagianA ? 'Bagian Dokinfo' : 'Bagian FPP';
                $badgeBagianClass = $isBagianA ? 'bg-label-primary' : 'bg-label-info';
            @endphp

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card chart-card h-100 shadow-sm">
                    <!-- Card Header -->
                    <div class="card-header pb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge {{ $badgeBagianClass }} fw-semibold px-2 py-1">
                                <i class="bx bx-tag me-1"></i>{{ $namaBagian }}
                            </span>
                            @if ($realisasiPersen >= 100)
                                <span class="badge bg-label-danger"><i class="bx bx-check-circle me-1"></i>100% Terserap</span>
                            @elseif ($realisasiPersen > 0)
                                <span class="badge bg-label-success"><i class="bx bx-trending-up me-1"></i>{{ number_format($realisasiPersen, 2, ',', '.') }}% Terserap</span>
                            @else
                                <span class="badge bg-label-secondary"><i class="bx bx-minus me-1"></i>0% Terserap</span>
                            @endif
                        </div>

                        <!-- Sub Kegiatan -->
                        <h6 class="mb-1 fw-bold text-dark text-truncate-2" title="{{ $npd->subKegiatan->kode_subkegiatan ?? '' }} - {{ $npd->subKegiatan->ket_subkegiatan ?? '' }}" data-bs-toggle="tooltip">
                            <span class="text-primary fw-semibold">{{ $npd->subKegiatan->kode_subkegiatan ?? '-' }}</span> &mdash; {{ $npd->subKegiatan->ket_subkegiatan ?? 'Sub Kegiatan' }}
                        </h6>

                        <!-- Rincian Belanja -->
                        <p class="text-muted small mb-2 text-truncate-1" title="{{ $npd->rincianBelanja->kode_rekening ?? '' }} - {{ $npd->rincianBelanja->keterangan ?? '' }}" data-bs-toggle="tooltip">
                            <i class="bx bx-receipt me-1 text-secondary"></i>
                            <span class="fw-semibold">{{ $npd->rincianBelanja->kode_rekening ?? '-' }}</span> &mdash; {{ $npd->rincianBelanja->keterangan ?? 'Rincian Belanja' }}
                        </p>

                        <!-- Pagu Anggaran Bar -->
                        <div class="stat-pill d-flex justify-content-between align-items-center">
                            <span class="text-muted small fw-medium">Total Pagu Anggaran:</span>
                            <span class="fw-bold text-dark fs-6">Rp{{ number_format($npd->total_anggaran, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Card Body with Chart -->
                    <div class="card-body pt-0 pb-2">
                        <div class="realisasiChart d-flex justify-content-center" data-chart="{{ json_encode($chartPayload) }}"></div>

                        <!-- Breakdown Pills -->
                        <div class="row g-2 mt-1">
                            <!-- Dana Terserap -->
                            <div class="col-6">
                                <div class="p-2 rounded bg-light border text-start h-100">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="d-inline-block rounded-circle bg-primary me-1" style="width: 8px; height: 8px;"></span>
                                        <span class="text-muted small" style="font-size: 11px;">Realisasi</span>
                                    </div>
                                    <div class="fw-bold text-primary small" style="font-size: 12.5px;">
                                        Rp{{ number_format($realisasiNominal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-muted" style="font-size: 10.5px;">
                                        Porsi: <strong>{{ number_format($realisasiPersen, 2, ',', '.') }}%</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Sisa Anggaran -->
                            <div class="col-6">
                                <div class="p-2 rounded bg-light border text-start h-100">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="d-inline-block rounded-circle bg-secondary me-1" style="width: 8px; height: 8px;"></span>
                                        <span class="text-muted small" style="font-size: 11px;">Sisa Pagu</span>
                                    </div>
                                    <div class="fw-bold text-dark small" style="font-size: 12.5px;">
                                        Rp{{ number_format($sisaNominal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-muted" style="font-size: 10.5px;">
                                        Porsi: <strong>{{ number_format($sisaPersen, 2, ',', '.') }}%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar Visual -->
                        <div class="progress mt-3" style="height: 6px;" title="Serapan: {{ number_format($realisasiPersen, 2, ',', '.') }}%" data-bs-toggle="tooltip">
                            <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: {{ min(100, $realisasiPersen) }}%;" aria-valuenow="{{ $realisasiPersen }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Card Footer Action -->
                    <div class="card-footer pt-1 pb-3 border-0">
                        <a href="{{ route('npd.detail', ['bagian' => ($npd->bagian === 'A' ? 'BagianDokinfo' : 'BagianFPP'), 'alokasi' => $npd->id]) }}" class="btn btn-sm btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="bx bx-search-alt-2 me-1"></i> Rincian Pengajuan NPD
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center p-5 shadow-sm">
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bx-folder-open fs-1"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold">Belum Ada Data Agenda Kegiatan</h5>
                    <p class="text-muted">Data Alokasi NPD belum ditambahkan untuk bagian ini.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
