@extends('layouts.admin')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('npd.pengajuan.action', ['bagian' => $bagian]) }}" method="POST" class="needs-validation"
        novalidate>
        @csrf
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Form Pengajuan Surat NPD</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <label class="col-sm-2 col-form-label" for="tanggal_pengajuan">Tanggal</label>
                    <div class="col-sm-4 col-md-2">
                        <input type="date" class="form-control @error('tanggal_pengajuan') is-invalid @enderror"
                            id="tanggal_pengajuan" name="tanggal_pengajuan" value="{{ date('Y-m-d') }}" readonly />
                    </div>
                </div>
                <div class="row mb-3">
                    {{-- <label class="col-sm-2 col-form-label" for="program"></label> --}}
                    <div class="col">
                        <select name="alokasi_npd_id" id="alokasi_npd"
                            class="form-select @error('alokasi_npd_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih NPD</option>
                            @foreach ($npds as $npd)
                                <option value="{{ $npd->id }}" data-subkegiatan="{{ $npd->subKegiatan }}"
                                    data-rincianbelanja="{{ $npd->rincianBelanja }}"
                                    data-sisa="{{ $npd->total_anggaran - $npd->realisasi }}">
                                    {{ $npd->subKegiatan->kode_subkegiatan }} - {{ $npd->subKegiatan->ket_subkegiatan }} |
                                    {{ $npd->rincianBelanja->kode_rekening }} - {{ $npd->rincianBelanja->keterangan }}
                                </option>
                            @endforeach
                        </select>
                        @error('alokasi_npd_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="program">Program</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="program" name="kode_program" placeholder="Kode"
                            disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_program" name="ket_program"
                            placeholder="Keterangan" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="kegiatan">Kegiatan</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="kegiatan" name="kode_kegiatan" placeholder="Kode"
                            disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_kegiatan" name="ket_kegiatan"
                            placeholder="Keterangan" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="subkegiatan">Sub Kegiatan</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="subkegiatan" name="kode_subkegiatan"
                            placeholder="Kode" disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_subkegiatan" name="ket_subkegiatan"
                            placeholder="Keterangan" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="rincian_belanja">Rincian Belanja</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="rincian_belanja" name="kode_rincian_belanja"
                            placeholder="Kode" disabled />
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="ket_rincian_belanja" name="ket_rincian_belanja"
                            placeholder="Keterangan" disabled />
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="uraian_kegiatan">Uraian Kegiatan</label>
                    <div class="col-sm-10">
                        <textarea name="uraian_kegiatan" id="uraian_kegiatan"
                            class="form-control @error('uraian_kegiatan') is-invalid @enderror" rows="3">{{ old('uraian_kegiatan') }}</textarea>
                        @error('uraian_kegiatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="anggaran">Anggaran</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('anggaran') is-invalid @enderror"
                                id="anggaran" name="anggaran" placeholder="0" value="{{ old('anggaran') ?? 0 }}" />
                            <input type="hidden" name="sisa" id="sisa" value="0">
                            @error('anggaran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
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
                    <h5>Nomor NPD:</h5>
                    <h3 class="h1 text-primary">{{ session('pengajuan') }}</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            @if (session('pengajuan'))
                $('#successModal').modal('show')
            @endif

            $('#alokasi_npd').change(function() {
                const selectedOpt = $(this).find('option:selected')
                const subKegiatanData = selectedOpt.data('subkegiatan')
                const rincianBelanjaData = selectedOpt.data('rincianbelanja')
                const sisaAnggaran = selectedOpt.data('sisa')

                $('#program').val(subKegiatanData.kode_program)
                $('#ket_program').val(subKegiatanData.ket_program)
                $('#kegiatan').val(subKegiatanData.kode_kegiatan)
                $('#ket_kegiatan').val(subKegiatanData.ket_kegiatan)
                $('#subkegiatan').val(subKegiatanData.kode_subkegiatan)
                $('#ket_subkegiatan').val(subKegiatanData.ket_subkegiatan)
                $('#rincian_belanja').val(rincianBelanjaData.kode_rekening)
                $('#ket_rincian_belanja').val(rincianBelanjaData.keterangan)
                $('#sisa').val(sisaAnggaran)
            })
        });
    </script>
@endpush
