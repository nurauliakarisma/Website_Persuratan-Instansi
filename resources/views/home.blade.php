@extends('layouts.admin')

@push('page-js')
    {{-- <script src="{{ asset('dist/js/pages/dashboards-analytics.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            let cardColor, headingColor, axisColor, shadeColor, borderColor;

            cardColor = config.colors.white;
            headingColor = config.colors.headingColor;
            axisColor = config.colors.axisColor;
            borderColor = config.colors.borderColor;

            $('.realisasiChart').each(function() {
                const {
                    realisasi,
                    sisa
                } = $(this).data('chart')

                new ApexCharts(this, {
                    chart: {
                        type: 'donut'
                    },
                    labels: ['Realisasi', 'Sisa Anggaran'],
                    series: [realisasi, sisa],
                    colors: [config.colors.primary, config.colors.secondary],
                    stroke: {
                        width: 5,
                        colors: cardColor
                    },
                    dataLabels: {
                        enabled: false,
                        formatter: function(val, opt) {
                            return parseInt(val) + '%';
                        }
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            right: 15
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '1.5rem',
                                        fontFamily: 'Public Sans',
                                        color: headingColor,
                                        offsetY: -15,
                                        formatter: function(val) {
                                            return parseInt(val) + '%';
                                        }
                                    },
                                    name: {
                                        offsetY: 20,
                                        fontFamily: 'Public Sans'
                                    },
                                    total: {
                                        show: true,
                                        fontSize: '0.8125rem',
                                        color: axisColor,
                                        label: 'Total',
                                        formatter: function(w) {
                                            return '100%';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }).render();
            });
        })
    </script>
@endpush

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
    <div class="row">
        @foreach ($npds as $npd)
            @php
                $realisasi = ($npd->realisasi / $npd->total_anggaran) * 100;
                $sisa = 100 - $realisasi;
                $data = [
                    'realisasi' => $realisasi,
                    'sisa' => $sisa,
                ];
            @endphp
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">{{ $npd->subKegiatan->kode_subkegiatan }}</h5>
                            <h5 class="m-0 me-2">{{ $npd->rincianBelanja->kode_rekening }}</h5>
                            <small class="text-muted">Total Anggaran
                                Rp{{ number_format($npd->total_anggaran, 2, ',', '.') }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="realisasiChart" data-chart="{{ json_encode($data) }}"></div>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->
        @endforeach
    </div>
@endsection
