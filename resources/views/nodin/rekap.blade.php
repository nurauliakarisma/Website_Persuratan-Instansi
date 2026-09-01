@extends('layouts.admin')

@push('page-css')
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <style>
        /* Clean Modern Calendar Styling */
        .fc {
            --fc-border-color: #eef1f6;
            --fc-page-bg-color: #ffffff;
            --fc-neutral-bg-color: #f8f9fa;
            --fc-today-bg-color: #f4f6ff;
            font-family: inherit;
        }
        .fc .fc-toolbar {
            margin-bottom: 1.25rem !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .fc .fc-toolbar-chunk {
            display: flex;
            align-items: center;
        }
        .fc .fc-toolbar-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2b3674;
            text-transform: capitalize;
            text-align: center;
        }
        .fc .fc-button {
            background-color: #696cff !important;
            border: 1px solid #696cff !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            box-shadow: none !important;
            transition: all 0.2s ease;
        }
        .fc .fc-button:hover,
        .fc .fc-button:active {
            background-color: #5f61e6 !important;
            border-color: #5f61e6 !important;
        }
        .fc .fc-col-header-cell {
            background-color: #f8f9fb;
            padding: 8px 0;
            font-weight: 700;
            font-size: 0.8rem;
            color: #697a8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #eef1f6;
        }
        .fc-theme-bootstrap5 .fc-scrollgrid {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #eef1f6;
        }
        .fc .fc-daygrid-day-number {
            font-size: 0.82rem;
            font-weight: 600;
            color: #566a7f;
            padding: 4px 6px;
        }
        .fc .fc-day-today {
            background-color: #f0f2ff !important;
        }
        .fc .fc-day-today .fc-daygrid-day-number {
            background-color: #696cff;
            color: #ffffff;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
            font-size: 0.72rem;
        }
        .fc-event {
            background-color: rgba(105, 108, 255, 0.12) !important;
            border: none !important;
            border-left: 3px solid #696cff !important;
            border-radius: 4px !important;
            padding: 2px 4px !important;
            margin: 2px 1px !important;
            color: #696cff !important;
            font-size: 0.72rem !important;
            font-weight: 600 !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .fc-event .fc-event-title {
            font-weight: 600;
            color: #566a7f;
        }
        .fc-event:hover {
            background-color: #696cff !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(105, 108, 255, 0.25);
        }
        .fc-event:hover .fc-event-title {
            color: #ffffff !important;
        }
        @media (max-width: 767.98px) {
            .fc .fc-toolbar-title {
                font-size: 1rem;
            }
            .fc .fc-col-header-cell {
                font-size: 0.68rem;
                padding: 4px 0;
            }
            .fc .fc-daygrid-day-number {
                font-size: 0.72rem;
                padding: 2px 4px;
            }
            .fc-event {
                font-size: 0.65rem !important;
                padding: 1px 3px !important;
            }
            .fc .fc-daygrid-day-frame {
                min-height: 52px;
            }
        }
    </style>
@endpush

@push('page-js')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.11/index.global.min.js'></script>
    <script>
        let calendar;
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                locale: 'id',
                dayMaxEvents: 2,
                events: {
                    url: `{{ route('nodin.rekap.list', $bagian) }}`,
                    method: 'GET',
                    extraParams: {
                        staff: `{{ $staff }}`
                    },
                    failure: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: 'Terjadi kesalahan saat mengambil agenda rekap NODIN.'
                        });
                    }
                },
                eventClick: (info) => {
                    const {
                        title,
                        start,
                        end,
                        extendedProps: {
                            perihal,
                            nomor,
                            an,
                            nama_penginput
                        }
                    } = info.event;

                    $('#detailNomorNodin').text(nomor || '-');
                    $('#detailSubject').text(title || '-');
                    $('#detailPerihal').text(perihal || '-');
                    $('#detailAtasNama').text(an || '-');
                    $('#detailPengaju').text(nama_penginput || '-');

                    // Format dates
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    const startDateStr = start ? new Date(start).toLocaleDateString('id-ID', options) : '-';
                    const endDateStr = end ? new Date(end).toLocaleDateString('id-ID', options) : startDateStr;
                    $('#detailPeriode').text(startDateStr === endDateStr ? startDateStr : `${startDateStr} s/d ${endDateStr}`);

                    $('#detailModal').modal('show');
                }
            });

            calendar.render();
        });
    </script>
@endpush

@section('content')
    <!-- Top Full-Width Search Staff Bar -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="" method="GET" class="w-100">
                <label for="staff" class="form-label fw-semibold text-muted small mb-2 d-flex align-items-center gap-1">
                    <i class="bx bx-search-alt-2 text-primary"></i> Cari / Filter Staff Pengaju NODIN:
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-primary">
                        <i class="bx bx-user fs-5"></i>
                    </span>
                    <select name="staff" id="staff" class="form-select border-start-0 ps-0" onchange="this.form.submit()" style="font-size: 0.9rem;">
                        <option value="">Semua Staff Pengaju</option>
                        @foreach ($staffs as $s)
                            <option value="{{ $s->nama }}" @selected($s->nama == $staff)>
                                {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                    @if ($staff !== '')
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger d-flex align-items-center gap-1 px-3" title="Hapus Filter">
                            <i class="bx bx-x"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Main Calendar Card -->
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-bottom py-3 px-3 px-sm-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="bx bx-calendar text-primary fs-5"></i>
                    Kalender Rekap Agenda NODIN
                </h6>
            </div>
            <button type="button" class="btn btn-xs btn-outline-primary px-3 py-1 fw-medium" onclick="calendar.today();">
                <i class="bx bx-calendar-check me-1"></i>Hari Ini
            </button>
        </div>
        <div class="card-body p-2 p-sm-4">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Modal Detail NODIN -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px;">
                <div class="modal-header border-bottom py-3 px-3">
                    <h6 class="modal-title mb-0 fw-bold d-flex align-items-center text-dark" id="detailModalLabel">
                        <i class="bx bx-file text-primary fs-4 me-2"></i>
                        Detail Agenda Nota Dinas
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-sm-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
                        <span class="badge bg-label-primary fs-6 fw-semibold text-wrap text-start" id="detailNomorNodin">-</span>
                        <span class="badge bg-label-success rounded-pill px-2 py-1">Disetujui</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold mb-1">Subject / Judul Agenda:</small>
                        <div class="fw-bold text-dark fs-6" id="detailSubject">-</div>
                    </div>

                    <div class="p-2 p-sm-3 bg-lighter rounded border mb-3" style="font-size: 0.85rem;">
                        <div class="mb-2">
                            <i class="bx bx-calendar me-1 text-primary"></i><strong>Periode Kegiatan:</strong>
                            <span class="text-dark d-block ms-4" id="detailPeriode">-</span>
                        </div>
                        <div class="mb-2">
                            <i class="bx bx-buildings me-1 text-secondary"></i><strong>Atas Nama:</strong>
                            <span class="text-dark d-block ms-4" id="detailAtasNama">-</span>
                        </div>
                        <div>
                            <i class="bx bx-user me-1 text-info"></i><strong>Staff Pengaju:</strong>
                            <span class="text-dark d-block ms-4" id="detailPengaju">-</span>
                        </div>
                    </div>

                    <div>
                        <small class="text-muted d-block fw-semibold mb-1">Perihal Lengkap:</small>
                        <div class="p-3 bg-white rounded border text-dark text-wrap" style="line-height: 1.6; font-size: 0.88rem;" id="detailPerihal">-</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-3 px-3">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

