<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SMBGarasiBMW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/login-assets/login-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-white p-0 m-0 overflow-hidden">

    <div class="container-fluid p-0">
        <div class="row g-0 vh-100">

            <div class="col-md-6 d-none d-md-block p-3">
                <div class="left-image-container h-100 w-100 position-relative overflow-hidden">
                    <div class="overlay d-flex flex-column justify-content-between p-5 h-100 text-white">
                        <div class="logo-area z-1">
                            <img src="{{ asset('assets/login-assets/login-logo.png') }}" alt="Logo"
                                style="height: 24px; vertical-align: middle;">
                            <span class="ms-2 fw-bold" style="letter-spacing: 1px;">GARASIBMW</span>
                        </div>
                        <div class="text-content z-1">
                            <h2 class="fw-bold mb-3" style="font-size: 2.5rem; line-height: 1.1; letter-spacing: -1px;">
                                COMMAND<br>THE GARAGE</h2>
                            <p class="small mb-1 text-white-50">Selamat datang di pusat kendali operasional GARASIBMW
                                Bandung.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 bg-white d-flex align-items-center justify-content-center">
                <div class="w-100" style="max-width: 500px;">
                    <div class="text-center mb-5">
                        <h3 class="fw-bold mb-1">SELAMAT DATANG</h3>
                        <p class="text-muted small">Masuk untuk akses dashboard</p>
                    </div>

                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label text-muted fw-semibold small">Email</label>
                            <input type="email" class="form-control form-control-lg custom-input" id="email" required
                                placeholder="Masukkan Email">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label text-muted fw-semibold small">Password</label>
                            <input type="password" class="form-control form-control-lg custom-input" id="password"
                                required placeholder="Masukkan Password">
                        </div>
                        <button type="submit" id="loginBtn"
                            class="btn btn-primary w-100 py-3 mt-2 fw-bold rounded-3 custom-btn">Masuk</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Ubah tombol jadi loading
            loginBtn.disabled = true;
            loginBtn.innerText = 'Tunggu sebentar...';

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                // Tembak ke API Backend lu (pake folder /api)
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (response.ok) {
                    // LOGIN SUKSES!
                    // 1. Simpan Token ke LocalStorage biar bisa dipake di halaman lain
                    localStorage.setItem('access_token', result.data.access_token);
                    localStorage.setItem('user_name', result.data.user.name);
                    localStorage.setItem('user_role', result.data.user.role);
                    localStorage.setItem('user_employees_id', result.data.user.employees_id ?? '');

                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil!',
                        text: 'Selamat datang, ' + result.data.user.name,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Lempar ke halaman dashboard frontend
                        window.location.href = '/beranda';
                    });

                } else {
                    // LOGIN GAGAL (Email/Pass salah)
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: result.message || 'Email atau password salah!'
                    });
                    loginBtn.disabled = false;
                    loginBtn.innerText = 'Masuk';
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal terhubung ke server backend. Pastikan Laravel sudah menyala.'
                });
                loginBtn.disabled = false;
                loginBtn.innerText = 'Masuk';
            }
        });
    </script>

    <script>
        // ── Tampilkan alert jika user diredirect dari auth/role guard ──
        document.addEventListener('DOMContentLoaded', function () {
            // Case 1: Belum login, coba akses halaman via URL langsung
            const noSession = sessionStorage.getItem('no_session');
            if (noSession) {
                sessionStorage.removeItem('no_session');
                Swal.fire({
                    icon: 'warning',
                    title: 'Sesi Tidak Ditemukan!',
                    text: 'Kamu harus login terlebih dahulu untuk mengakses halaman tersebut.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#1273EB',
                });
                return;
            }

            // Case 2: Sudah login tapi role tidak punya akses (karyawan/guest)
            const blockedRole = sessionStorage.getItem('blocked_role');
            if (blockedRole) {
                sessionStorage.removeItem('blocked_role');
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    text: 'Role "' + blockedRole + '" tidak memiliki izin untuk mengakses sistem ini. Hubungi administrator.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#1273EB',
                });
            }
        });
    </script>
</body>

</html>