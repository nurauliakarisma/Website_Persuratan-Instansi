@extends('layouts.admin')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('change-password.action') }}" method="POST" class="needs-validation" novalidate>
        @method('put')
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="new_password">Password Baru</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                            id="new_password" name="new_password" placeholder="Masukkan password baru" />
                        @error('new_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                            id="new_password_confirmation" name="new_password_confirmation"
                            placeholder="Masukkan kembali password baru" />
                        @error('new_password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="mt-5 d-grid col">
                    <button type="submit" class="btn btn-warning btn-lg">Set Password Baru</button>
                </div>
            </div>
        </div>
    </form>
@endsection
