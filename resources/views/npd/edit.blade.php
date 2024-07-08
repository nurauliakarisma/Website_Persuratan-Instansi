@extends('layouts.admin')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('npd.edit.action', ['bagian' => $bagian, 'alokasiNPD' => $alokasiNPD->id]) }}" method="POST"
        class="needs-validation" novalidate>
        @method('put')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="program">Program</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="program" name="kode_program"
                            value="{{ $alokasiNPD->subKegiatan->kode_program }}" disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_program" name="ket_program"
                            value="{{ $alokasiNPD->subKegiatan->ket_program }}" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="kegiatan">Kegiatan</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="kegiatan" name="kode_kegiatan"
                            value="{{ $alokasiNPD->subKegiatan->kode_kegiatan }}" disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_kegiatan" name="ket_kegiatan"
                            value="{{ $alokasiNPD->subKegiatan->ket_kegiatan }}" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="subkegiatan">Sub Kegiatan</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="subkegiatan" name="kode_subkegiatan"
                            value="{{ $alokasiNPD->subKegiatan->kode_subkegiatan }}" disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_subkegiatan" name="ket_subkegiatan"
                            value="{{ $alokasiNPD->subKegiatan->ket_subkegiatan }}" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="rincian_belanja">Rincian Belanja</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="rincian_belanja" name="kode_rincian_belanja"
                            value="{{ $alokasiNPD->rincianBelanja->kode_rekening }}" disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_rincian_belanja" name="ket_rincian_belanja"
                            value="{{ $alokasiNPD->rincianBelanja->keterangan }}" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="anggaran">Total Anggaran</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('anggaran') is-invalid @enderror"
                                id="anggaran" name="anggaran" value="{{ $alokasiNPD->total_anggaran }}" />
                        </div>
                        @error('anggaran')
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
@endsection
