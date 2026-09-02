@extends(auth()->check() && auth()->user()->tipe === 'Staff' ? 'layouts.single' : 'layouts.admin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom py-3 d-flex align-items-center gap-2">
                    <i class="bx bx-lock-alt fs-4 text-warning"></i>
                    <div>
                        <h5 class="card-title fw-bold text-dark mb-0">Ubah Password Akun</h5>
                        <small class="text-muted">Perbarui kata sandi akun Anda untuk menjaga keamanan akses</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('change-password.action') }}" method="POST" class="needs-validation" novalidate>
                        @method('put')
                        @csrf
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label fw-semibold text-dark" for="new_password">Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    id="new_password" name="new_password" placeholder="Masukkan password baru minimal 6 karakter" required />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('new_password')
                                <div class="invalid-feedback d-block mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4 form-password-toggle">
                            <label class="form-label fw-semibold text-dark" for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                    id="new_password_confirmation" name="new_password_confirmation"
                                    placeholder="Ulangi penulisan password baru" required />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback d-block mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-semibold btn-lg">
                                <i class="bx bx-check-shield me-1"></i>Simpan Password Baru
                            </button>
                            @if(auth()->check() && auth()->user()->tipe === 'Staff')
                                <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-x me-1"></i>Batal
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-x me-1"></i>Batal
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

