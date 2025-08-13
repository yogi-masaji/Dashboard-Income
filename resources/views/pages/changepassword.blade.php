@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $chiselVersion = session('selected_location_chisel_Version', 'Chisel Version Tidak Diketahui');
        $systemCode = session('selected_location_system', 'System Code Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp

    <style>
        /* Light Mode (Default) */
        .change-password-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            color: #212529;
        }

        .change-password-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .change-password-card .form-control {
            background-color: #fff;
            color: #495057;
            border: 1px solid #ced4da;
        }

        .change-password-card .form-control:focus {
            background-color: #fff;
            color: #495057;
        }

        .change-password-card label {
            color: #007bff;
            /* Warna label biru seperti di file asli */
        }

        /* Dark Mode Styles */
        .mode-gelap .change-password-card {
            background-color: #2a3038;
            /* Warna background card gelap */
            border: 1px solid #454d55;
            color: #e9ecef;
        }

        .mode-gelap .change-password-card .card-header {
            background-color: #343a40;
            /* Warna header card lebih gelap */
            border-bottom: 1px solid #454d55;
        }

        .mode-gelap .change-password-card .form-control {
            background-color: #343a40;
            color: #e9ecef;
            border: 1px solid #454d55;
        }

        .mode-gelap .change-password-card .form-control:focus {
            background-color: #343a40;
            color: #e9ecef;
        }

        .mode-gelap .change-password-card label {
            color: #55acee;
            /* Warna label biru muda untuk kontras */
        }

        .mode-gelap .alert-success {
            background-color: #28a745;
            color: #fff;
        }

        .mode-gelap .alert-danger {
            background-color: #dc3545;
            color: #fff;
        }
    </style>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card change-password-card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0 font-weight-bold">Ubah Password</h5>
                    </div>
                    <div class="card-body">
                        {{-- Alert untuk menampilkan response --}}
                        <div id="response-message" class="alert d-none" role="alert">
                            <h6 class="alert-heading"></h6>
                            <p></p>
                        </div>

                        <form id="change-password-form" method="POST" action="{{ route('password.change') }}">
                            @csrf
                            <div class="form-group">
                                <label for="current_password">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                    required placeholder="Masukkan password Anda saat ini">
                            </div>
                            <div class="form-group mt-3">
                                <label for="new_password">Password Baru</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required
                                    minlength="8" placeholder="Minimal 8 karakter">
                            </div>
                            <div class="form-group mt-3">
                                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    name="new_password_confirmation" required placeholder="Ketik ulang password baru Anda">
                            </div>
                            <button type="submit" id="submit-button" class="btn btn-primary mt-3">
                                <span id="button-text">Simpan Perubahan</span>
                                <span id="button-spinner" class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('change-password-form');
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            const buttonSpinner = document.getElementById('button-spinner');
            const responseMessage = document.getElementById('response-message');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Tampilkan spinner dan disable tombol
                buttonText.classList.add('d-none');
                buttonSpinner.classList.remove('d-none');
                submitButton.disabled = true;
                responseMessage.classList.add('d-none');

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({
                        status,
                        body
                    }) => {
                        responseMessage.classList.remove('d-none');
                        const titleEl = responseMessage.querySelector('h6');
                        const messageEl = responseMessage.querySelector('p');

                        titleEl.textContent = body.title || 'Response';
                        messageEl.textContent = body.message || 'Something went wrong.';

                        if (status >= 200 && status < 300) {
                            responseMessage.classList.remove('alert-danger');
                            responseMessage.classList.add('alert-success');
                            form.reset(); // Kosongkan form jika berhasil
                        } else {
                            responseMessage.classList.remove('alert-success');
                            responseMessage.classList.add('alert-danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        responseMessage.classList.remove('d-none', 'alert-success');
                        responseMessage.classList.add('alert-danger');
                        responseMessage.querySelector('h6').textContent = 'Error';
                        responseMessage.querySelector('p').textContent =
                            'An unexpected error occurred.';
                    })
                    .finally(() => {
                        // Kembalikan tombol ke state normal
                        buttonText.classList.remove('d-none');
                        buttonSpinner.classList.add('d-none');
                        submitButton.disabled = false;
                    });
            });
        });
    </script>
@endsection
