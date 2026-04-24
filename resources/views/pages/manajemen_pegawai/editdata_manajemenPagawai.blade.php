@extends('layouts.master')

@section('title', 'Edit Data Pegawai')
@section('title_header', 'Manajemen Pegawai | Edit Pegawai')

@section('form_icon')
    <div class="w-12 h-12 bg-[#FFA500] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-orange-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Mengubah Data Pegawai')

@section('form_fields')
    <form id="editPegawaiForm" class="space-y-6">

        {{-- Section 1: Informasi Pribadi --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-5 h-5 rounded-full bg-[#1273EB] flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Pribadi Pegawai</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" required placeholder="Masukkan nama lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400 focus:border-[#1273EB] transition">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                    <input type="date" id="join_date" required
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] focus:border-[#1273EB] transition">
                </div>
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" id="birth_date" required
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] focus:border-[#1273EB] transition">
                </div>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Alamat <span class="text-red-500">*</span></label>
                <input type="text" id="address" required placeholder="Masukkan alamat lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400 focus:border-[#1273EB] transition">
            </div>
        </div>

        {{-- Section 2: Informasi Akun --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-5 h-5 rounded-full bg-[#1273EB] flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Akun Pegawai</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" required placeholder="nama@email.com"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400 focus:border-[#1273EB] transition">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Password
                    <span class="text-gray-400 font-normal text-[11px] ml-1">(kosongkan jika tidak ingin diubah)</span>
                </label>
                <input type="password" id="password" placeholder="••••••••"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400 focus:border-[#1273EB] transition">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Roles <span class="text-red-500">*</span></label>
                <select id="role" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] focus:border-[#1273EB] transition">
                    <option value="" disabled>Pilih Role</option>
                    <option value="pemilik_bengkel">Pemilik Bengkel</option>
                    <option value="finance">Finance</option>
                    <option value="kepala_bengkel">Kepala Bengkel</option>
                    <option value="kepala_admin">Kepala Admin</option>
                    <option value="admin">Admin</option>
                    <option value="karyawan">Karyawan</option>
                </select>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Status <span class="text-red-500">*</span></label>
                <select id="status" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] focus:border-[#1273EB] transition">
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
            </div>
        </div>

        {{-- Section 3: Pemasukan Tetap --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-5 h-5 rounded-full bg-[#1273EB] flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Pemasukan Tetap</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Gaji Pokok</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-gray-400 font-medium select-none">Rp</span>
                    <input type="text" id="base_salary" inputmode="numeric" pattern="[0-9]*" placeholder="0"
                        class="w-full pl-10 pr-4 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400 focus:border-[#1273EB] transition">
                </div>
            </div>
        </div>

    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl'       => route('manajemen-pegawai.index'),
        'sectionTitle'  => 'Edit Data Pegawai',
        'submitBtnText' => 'Update Data'
    ])

    <script>
        const pathArray = window.location.pathname.split('/');
        const employeeId = pathArray[pathArray.length - 1];
        const token = localStorage.getItem('access_token');

        // ─── Blokir input non-angka ───────────────────────────────
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('keydown', (e) => {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) e.preventDefault();
            });
            input.addEventListener('paste', (e) => {
                if (!/^[0-9]+$/.test(e.clipboardData.getData('text'))) e.preventDefault();
            });
        });

        // ─── Load data existing saat halaman dibuka ───────────────
        async function loadEmployeeData() {
            try {
                const response = await fetch(`/api/employees/${employeeId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    const d = result.data ?? result;

                    document.getElementById('name').value        = d.name        ?? '';
                    document.getElementById('join_date').value   = d.join_date   ?? '';
                    document.getElementById('birth_date').value  = d.birth_date  ?? '';
                    document.getElementById('address').value     = d.address     ?? '';
                    document.getElementById('email').value       = d.email       ?? '';
                    document.getElementById('base_salary').value = d.base_salary ?? '';

                    if (d.role)   document.getElementById('role').value   = d.role;
                    // status: DB simpan 1/0, set value select sesuai nilainya
                    document.getElementById('status').value = (d.status == 1 || d.status === true) ? '1' : '0';

                } else {
                    Swal.fire('Gagal', result.message || 'Data pegawai tidak ditemukan.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Gagal memuat data dari server.', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', loadEmployeeData);

        // ─── Submit Update ────────────────────────────────────────
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const passwordVal = document.getElementById('password').value;

            const data = {
                name:        document.getElementById('name').value,
                address:     document.getElementById('address').value,
                email:       document.getElementById('email').value,
                role:        document.getElementById('role').value,
                base_salary: Number(document.getElementById('base_salary').value) || 0,
                status:      Number(document.getElementById('status').value), // kirim sebagai 1 atau 0
            };

            // Hanya kirim password jika diisi
            if (passwordVal.trim() !== '') {
                data.password = passwordVal;
            }

            try {
                Swal.fire({
                    title: 'Memperbarui data...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const response = await fetch(`/api/employees/${employeeId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    // Cek apakah yang diedit adalah akun user yang sedang login
                    const loggedInId = localStorage.getItem('user_employees_id');
                    if (loggedInId && String(loggedInId) === String(employeeId)) {
                        const newName = document.getElementById('name').value;
                        const newRole = document.getElementById('role').value;
                        refreshUserHeader(newName, newRole);
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data pegawai berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('manajemen-pegawai.index') }}";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Update',
                        text: result.message || 'Cek kembali inputan lu brok!'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Koneksi API bermasalah.', 'error');
            }
        };
    </script>
@endsection
