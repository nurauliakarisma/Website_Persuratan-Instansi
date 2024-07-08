@extends('layouts.admin')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('npd.tambah.action', ['bagian' => $bagian]) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="subkegiatan">Sub Kegiatan</label>
                    <div class="col-10">
                        <select name="subkegiatan_id" id="subkegiatan"
                            class="form-select @error('subkegiatan_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Sub Kegiatan</option>
                            @foreach ($subs as $sub)
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
                    <div class="col-10">
                        <select name="rincian_belanja_id" id="rincian_belanja"
                            class="form-select @error('rincian_belanja_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Rincian Belanja</option>
                            @foreach ($rincians as $rincian)
                                <option value="{{ $rincian->id }}">
                                    {{ $rincian->kode_rekening }} - {{ $rincian->keterangan }}
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
                    <label class="col-sm-2 col-form-label" for="anggaran">Total Anggaran</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('anggaran') is-invalid @enderror"
                                id="anggaran" name="anggaran" placeholder="0" value="{{ old('anggaran') ?? 0 }}" />
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
