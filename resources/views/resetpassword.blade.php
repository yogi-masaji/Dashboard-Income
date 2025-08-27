<!doctype html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="/vendor/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Reset Password</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('/storage/logo-cp-ico.png') }}" />
    <link rel="stylesheet" href="/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/vendor/css/pages/page-auth.css" />
    <script src="/vendor/js/helpers.js"></script>
    <script src="/vendor/js/config.js"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <div class="app-brand justify-content-center">
                            <img src="{{ asset('/storage/logo-real.png') }}" alt="logo" height="70">
                        </div>
                        <h4 class="mb-2 mt-2">Reset Password 🔒</h4>
                        <p class="mb-4">Silakan masukkan password baru Anda. Minimal 8 karakter.</p>

                        <form id="formAuthentication" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            {{-- Input tersembunyi untuk token dan email --}}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label text-dark" for="password">Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label text-dark" for="password-confirm">Konfirmasi Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password-confirm" class="form-control"
                                        name="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <button class="btn btn-primary d-grid w-100">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert untuk notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Menampilkan notifikasi dari session jika ada --}}
    @if (session('status'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Selesai!',
                text: '{{ session('status') }}',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Arahkan ke halaman login setelah notifikasi ditutup
                    window.location.href = "{{ route('login') }}";
                }
            });
        </script>
    @endif

    <script src="/vendor/libs/jquery/jquery.js"></script>
    <script src="/vendor/js/bootstrap.js"></script>
    <script src="/vendor/js/main.js"></script>
</body>

</html>
