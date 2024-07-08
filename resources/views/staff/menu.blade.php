@extends('layouts.single')

@section('content')
    @if ($bagian == 'A')
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="card-title">NPD</h4>
                    </div>
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-center">
                        <img src="{{ asset('images/menus2.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('npd.pengajuan', 'A') }}" class="btn btn-warning">Ajukan Nomor
                            NPD</a>
                        <a href="{{ route('npd.detail', ['bagian' => 'A', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary">Lihat Nomor NPD</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="card-title">NODIN</h4>
                    </div>
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-center">
                        <img src="{{ asset('images/menus2.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('nodin.pengajuan', 'A') }}" class="btn btn-warning">Ajukan Nomor NODIN</a>
                        <a href="{{ route('nodin.detail', ['bagian' => 'A', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary">Lihat Nomor NODIN</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100">
                    <div class="card-header text-center">
                        <h4 class="card-title">MEDIA</h4>
                    </div>
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-start">
                        <img src="{{ asset('images/menus2.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('media.create') }}" class="btn btn-warning">Ajukan Publikasi Media</a>
                        <a href="{{ route('media.show', ['prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary">Lihat Publikasi Media</a>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($bagian == 'B')
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="card-title">NPD</h4>
                    </div>
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-center">
                        <img src="{{ asset('images/menus2.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('npd.pengajuan', 'B') }}" class="btn btn-warning">Ajukan Nomor NPD</a>
                        <a href="{{ route('npd.detail', ['bagian' => 'B', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary">Lihat Nomor NPD</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="card-title">NODIN</h4>
                    </div>
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-center">
                        <img src="{{ asset('images/menus2.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('nodin.pengajuan', 'B') }}" class="btn btn-warning">Ajukan Nomor NODIN</a>
                        <a href="{{ route('nodin.detail', ['bagian' => 'B', 'prev_url' => url()->full()]) }}"
                            class="btn btn-outline-primary">Lihat Nomor NODIN</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row justify-content-center mt-5">
            <div class="col-6 col-md-4">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-center">
                        <img src="{{ asset('images/menus1.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('menu.index', ['bagian' => 'A', 'prev_url' => route('menu.index')]) }}"
                            class="h4 card-title stretched-link">Bagian Dokinfo</a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-3 align-items-stretch justify-content-center">
                        <img src="{{ asset('images/menus1.png') }}" alt="cover" class="img-fluid rounded">
                        <a href="{{ route('menu.index', ['bagian' => 'B', 'prev_url' => route('menu.index')]) }}"
                            class="h4 card-title stretched-link">Bagian FPP</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
