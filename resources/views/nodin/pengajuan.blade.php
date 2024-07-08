@extends('layouts.admin')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('nodin.pengajuan.action', ['bagian' => $bagian]) }}" method="POST" class="needs-validation"
        novalidate>
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Form Pengajuan Surat NODIN</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="tanggal_pengajuan">Tanggal</label>
                    <div class="col-auto">
                        <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan"
                            value="{{ date('Y-m-d') }}" readonly />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="index_kegiatan">Index Kegiatan</label>
                    <div class="col-sm-10">
                        <input type="hidden" name="nomor_index" id="nomor_index">
                        <select name="index_kegiatan_id" id="index_kegiatan"
                            class="form-select @error('index_kegiatan_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Index Kegiatan</option>
                            @foreach ($indexes as $idx)
                                <option value="{{ $idx->id }}" data-nomor="{{ $idx->kode }}">
                                    {{ $idx->kode }} - {{ $idx->keterangan }}
                                </option>
                            @endforeach
                        </select>
                        @error('index_kegiatan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="subkegiatan">Sub Kegiatan</label>
                    <div class="col-sm-10">
                        <select name="subkegiatan_id" id="subkegiatan"
                            class="form-select @error('subkegiatan_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Sub Kegiatan</option>
                            @foreach ($subkegiatans as $sub)
                                <option value="{{ $sub->id }}">
                                    {{ $sub->kode_subkegiatan }} - {{ $sub->ket_subkegiatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('subkegiatan_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="rincian_belanja">Rincian Belanja</label>
                    <div class="col-sm-10">
                        <select name="rincian_belanja_id" id="rincian_belanja"
                            class="form-select @error('rincian_belanja_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Rincian Belanja</option>
                            @foreach ($rincians as $rinci)
                                <option value="{{ $rinci->id }}">
                                    {{ $rinci->kode_rekening }} - {{ $rinci->keterangan }}
                                </option>
                            @endforeach
                        </select>
                        @error('rincian_belanja_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="subject">Subject</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                            name="subject" value="{{ old('subject') }}" />
                        @error('subject')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="perihal">Perihal</label>
                    <div class="col-sm-10">
                        <textarea name="perihal" id="perihal" class="form-control @error('perihal') is-invalid @enderror" rows="3">{{ old('perihal') }}</textarea>
                        @error('perihal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="tanggal_mulai">Tanggal Mulai</label>
                    <div class="col-sm-4 col-md-2">
                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" />
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <label class="col-sm-2 col-form-label offset-0 offset-md-1" for="tanggal_selesai">Tanggal
                        Selesai</label>
                    <div class="col-sm-4 col-md-2">
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" />
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="atas_nama">Atas Nama</label>
                    <div class="col-sm-10" id="an-container">
                        <select name="atas_nama[]" id="atas_nama"
                            class="form-select  @error('atas_nama') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Staff</option>
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->nama }}">{{ $staff->nama }}</option>
                            @endforeach
                        </select>
                        @error('atas_nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        {{-- <div class="input-group mt-3" id="an-select">
                            <select name="atas_nama[]" id="atas_nama" class="form-select">
                                <option value="" disabled selected>Pilih Staff</option>
                            </select>
                            <button class="btn btn-outline-danger">&dash;</button>
                        </div> --}}
                    </div>
                </div>
                <div class="row mb-5 px-3">
                    <button type="button" id="btnAdd"
                        class="btn btn-outline-primary btn-sm col offset-sm-2">&plus;</button>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="nama_penginput">Nama Penginput</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control @error('nama_penginput') is-invalid @enderror"
                            id="nama_penginput" name="nama_penginput" value="{{ request()->user()->nama }}" readonly />
                        @error('nama_penginput')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="mt-5 d-grid col">
                    <button type="submit" class="btn btn-warning btn-lg">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="successModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Pengajuan Surat Berhasil</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body text-center">
                    <h5>Nomor NODIN:</h5>
                    <h3 class="h2 text-primary">{{ session('pengajuan') }}</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cloning element --}}
    <div class="input-group mt-3" id="an-select" style="display: none;">
        <select name="atas_nama[]" id="atas_nama" class="form-select">
            <option value="" disabled selected>Pilih Staff</option>
            @foreach ($staffs as $staff)
                <option value="{{ $staff->nama }}">{{ $staff->nama }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-danger btn-remove">&dash;</button>
    </div>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            @if (session('pengajuan'))
                $('#successModal').modal('show')
            @endif

            $('#index_kegiatan').change(function() {
                const selectedOpt = $(this).find('option:selected')
                const nomor = selectedOpt.data('nomor')

                $('#nomor_index').val(nomor)
            })

            $('#btnAdd').click(() => {
                const el = $('#an-select')
                const clone = el.clone().removeAttr('id').show()
                $('#an-container').append(clone)
            })

            $('#an-container').on('click', '.btn-remove', function() {
                $(this).closest('.input-group').remove()
            })
        });
    </script>
@endpush
