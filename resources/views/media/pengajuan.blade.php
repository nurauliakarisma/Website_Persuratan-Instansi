@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('media.pengajuan.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Form Pengajuan Publikasi Media</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="media">Media</label>
                    <div class="col">
                        <select name="media_id" id="media" class="form-select @error('media_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Media</option>
                            @foreach ($medias as $media)
                                <option value="{{ $media->id }}">{{ $media->nama }} | Rp
                                    {{ number_format($media->harga_total, 2, ',', '.') }}</option>
                            @endforeach
                        </select>
                        @error('media_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="tanggal_tayang">Tanggal Tayang</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('tanggal_tayang') is-invalid @enderror"
                            id="tanggal_tayang" name="tanggal_tayang" placeholder="0"
                            value="{{ old('tanggal_tayang') ?? date('Y-m-d') }}" />
                        @error('tanggal_tayang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="nominal_publikasi">Nominal Publikasi</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('nominal_publikasi') is-invalid @enderror"
                                id="nominal_publikasi" name="nominal_publikasi" placeholder="0"
                                value="{{ old('nominal_publikasi') ?? 0 }}" />
                            @error('nominal_publikasi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="nominal_fotocopy">Nominal Fotocopy</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('nominal_fotocopy') is-invalid @enderror"
                                id="nominal_fotocopy" name="nominal_fotocopy" placeholder="0"
                                value="{{ old('nominal_fotocopy') ?? 0 }}" />
                            @error('nominal_fotocopy')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="judul">Judul Publikasi</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul"
                            name="judul" value="{{ old('judul') ?? '' }}" />
                        @error('judul')
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
