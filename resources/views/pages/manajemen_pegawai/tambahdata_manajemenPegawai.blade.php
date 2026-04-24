@extends('layouts.master')

@section('title', 'Tambah Data Pegawai')
@section('title_header', 'Manajemen Pegawai')

@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Membuat Akun Pegawai Baru')

@section('form_fields')
    <form id="addPegawaiForm" class="space-y-6">

        {{-- Informasi Pribadi Pegawai --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Pribadi Pegawai</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" required placeholder="Masukan nama lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                        Tanggal Bergabung <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="join_date" required
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                </div>
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="birth_date" required
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                </div>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <input type="text" id="address" required placeholder="Masukan alamat lengkap"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>
        </div>

        {{-- Informasi Akun Pegawai --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Akun Pegawai</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" required placeholder="nama@email.com"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password" required placeholder="••••••••"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                    Roles <span class="text-red-500">*</span>
                </label>
                <select id="role" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="pemilik_bengkel">Pemilik Bengkel</option>
                    <option value="finance">Finance</option>
                    <option value="kepala_bengkel">Kepala Bengkel</option>
                    <option value="kepala_admin">Kepala Admin</option>
                    <option value="admin">Admin</option>
                    <option value="karyawan">Karyawan</option>
                </select>
            </div>
        </div>

        {{-- Gaji Pokok --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Pemasukan Tetap</h3>
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Gaji Pokok</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-gray-400 font-medium select-none">Rp</span>
                    <input type="text" id="base_salary" inputmode="numeric" pattern="[0-9]*" placeholder="0"
                        class="w-full pl-10 pr-4 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
                </div>
            </div>
        </div>

    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('manajemen-pegawai.index'),
        'submitBtnText' => 'Simpan Data'
    ])

    <script>
        // ─── Blokir input non-angka pada Gaji Pokok ──────────────
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('keydown', (e) => {
                const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                    e.preventDefault();
                }
            });
            input.addEventListener('paste', (e) => {
                const pasted = e.clipboardData.getData('text');
                if (!/^[0-9]+$/.test(pasted)) e.preventDefault();
            });
        });

        // ─── Tambah Pendapatan Lain ───────────────────────────────
        let extraCount = 0;

        function tambahPendapatan() {
            extraCount++;
            const container = document.getElementById('extra_incomes');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';
            div.id = `extra_${extraCount}`;
            div.innerHTML = `
                <input type="text" placeholder="Nama pendapatan"
                    class="flex-1 px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400"
                    id="extra_name_${extraCount}">
                <input type="text" inputmode="numeric" pattern="[0-9]*" placeholder="Nominal"
                    class="flex-1 px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400"
                    id="extra_amount_${extraCount}">
                <button type="button" onclick="document.getElementById('extra_${extraCount}').remove()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 hover:bg-red-100 text-red-400 transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(div);
        }

        // ─── Kumpulkan extra incomes ──────────────────────────────
        function collectExtraIncomes() {
            const items = [];
            document.querySelectorAll('[id^="extra_name_"]').forEach(el => {
                const idx    = el.id.replace('extra_name_', '');
                const name   = el.value.trim();
                const amount = Number(document.getElementById(`extra_amount_${idx}`)?.value) || 0;
                if (name) items.push({ name, amount });
            });
            return items;
        }

        // ─── Submit ───────────────────────────────────────────────
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('access_token');

            const data = {
                name:        document.getElementById('name').value,
                join_date:   document.getElementById('join_date').value,
                birth_date:  document.getElementById('birth_date').value,
                address:     document.getElementById('address').value,
                email:       document.getElementById('email').value,
                password:    document.getElementById('password').value,
                role:        document.getElementById('role').value,
                base_salary: Number(document.getElementById('base_salary').value) || 0,
            };

            try {
                Swal.fire({
                    title: 'Menyimpan data...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const response = await fetch('/api/employees', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data pegawai baru berhasil disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('manajemen-pegawai.index') }}";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Simpan',
                        text: result.message || 'Cek kembali inputan lu brok!'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
            }
        };
    </script>
@endsection