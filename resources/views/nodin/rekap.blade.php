@extends('layouts.admin')

@push('page-css')
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
@endpush

@push('page-js')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = $('#detailModal')
            detailModal.on('hidden.bs.modal', e => {
                $('#nomorNodin').val('')
                $('#subject').val('')
                $('#perihal').val('')
                $('#atas_nama').val('')
            })

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: {
                    url: `{{ route('nodin.rekap.list', $bagian) }}`,
                    methode: 'GET',
                    extraParams: {
                        staff: `{{ $staff }}`
                    },
                    failure: function() {
                        alert('there was an error while fetching events!');
                    }
                },
                eventClick: (info) => {
                    const {
                        title,
                        extendedProps: {
                            perihal,
                            nomor,
                            an
                        }
                    } = info.event

                    detailModal.on('show.bs.modal', e => {
                        $('#nomorNodin').val(nomor)
                        $('#subject').val(title)
                        $('#perihal').val(perihal)
                        $('#atas_nama').val(an)
                    })

                    detailModal.modal('show')
                }
            });
            calendar.render();
        });
    </script>
@endpush

@section('content')
    <form action="" method="GET" class="mb-5">
        <div class="d-flex justify-content-between align-items-center gap-4 mb-1">
            <select name="staff" id="staff" class="form-select">
                <option value="">Pilih Staff</option>
                @foreach ($staffs as $s)
                    <option value="{{ $s->nama }}" @selected($s->nama == $staff)>
                        {{ $s->nama }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-outline-primary">Filter</button>
        </div>
        @if ($staff !== '')
            <a href="{{ url()->current() }}" style="font-size: 12pt">
                <i class="bx bx-x"></i>
                <span>Clear filter</span>
            </a>
        @endif
    </form>

    {{-- <div class="alert alert-warning">Data nodin tidak tersedia.</div> --}}
    <div id="calendar"></div>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="detailModalLabel">Detail NODIN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nomorNodin" class="form-label">Nomor</label>
                        <input type="text" readonly class="form-control-plaintext" id="nomorNodin">
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" readonly class="form-control-plaintext" id="subject">
                    </div>
                    <div class="mb-3">
                        <label for="perihal" class="form-label">Perihal</label>
                        <textarea readonly class="form-control-plaintext" id="perihal"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="atas_nama" class="form-label">Atas Nama</label>
                        <input type="text" readonly class="form-control-plaintext" id="atas_nama">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
