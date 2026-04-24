@extends('layouts.master')

@section('title', 'Tambah Pendataan Izin')
@section('title_header', 'Kepegawaian | Pendataan Izin')

@section('form_icon')
    <div
        class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Izin Keterlambatan Baru')

@section('form_fields')
    <form id="addIzinForm" class="space-y-4">

        {{-- Nama Karyawan --}}
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Nama Karyawan <span class="text-red-500">*</span>
            </label>
            <select id="employee_id" required
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[#213F5C]">
                <option value="" disabled selected>Pilih nama karyawan</option>
                {{-- Options will be loaded dynamically --}}
            </select>
        </div>

        {{-- Tanggal Terlambat --}}
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Tanggal Terlambat <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="date" id="late_date" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[#213F5C] pr-12">
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-[#1273EB]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Alasan --}}
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Alasan</label>
            <textarea id="reason" rows="4"
                placeholder="Masukan Alasan"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[#213F5C] resize-none"></textarea>
        </div>

    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('izin-terlambat.index'),
        'submitBtnText' => 'Simpan Data'
    ])

    <script>
        // ── Load daftar karyawan ke dropdown ──────────────────────────
        async function loadEmployees() {
            const token = localStorage.getItem('access_token');
            try {
                const response = await fetch('/api/employees', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await response.json();
                const select = document.getElementById('employee_id');

                const employees = result.data ?? result; // sesuaikan struktur response API
                employees.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.id;
                    option.textContent = emp.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Gagal memuat data karyawan:', error);
            }
        }

        // ── Set default tanggal hari ini ──────────────────────────────
        function setDefaultDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('late_date').value = today;
        }

        // ── Submit form ───────────────────────────────────────────────
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('access_token');

            const data = {
                employee_id : document.getElementById('employee_id').value,
                late_date   : document.getElementById('late_date').value,
                reason      : document.getElementById('reason').value,
            };

            // Validasi field wajib
            if (!data.employee_id || !data.late_date) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Nama karyawan dan tanggal terlambat wajib diisi!'
                });
                return;
            }

            try {
                // Tampilkan loading
                Swal.fire({
                    title: 'Menyimpan data...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const response = await fetch('/api/late-permits', {
                    method: 'POST',
                    headers: {
                        'Accept'        : 'application/json',
                        'Content-Type'  : 'application/json',
                        'Authorization' : `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        icon              : 'success',
                        title             : 'Berhasil!',
                        text              : 'Data izin keterlambatan berhasil disimpan.',
                        timer             : 2000,
                        showConfirmButton : false
                    });
                    window.location.href = "{{ route('izin-terlambat.index') }}";
                } else {
                    Swal.fire({
                        icon  : 'error',
                        title : 'Gagal Simpan',
                        text  : result.message || 'Terjadi kesalahan, coba lagi.'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
            }
        };

        // ── Init ──────────────────────────────────────────────────────
        loadEmployees();
        setDefaultDate();
    </script>
@endsection