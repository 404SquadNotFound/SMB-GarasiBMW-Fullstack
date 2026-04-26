@extends('layouts.master')

@section('title', 'Tambah Jenis Mesin')
@section('title_header', 'Master Data | Jenis Mesin')

@section('form_icon')
    <div
        class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Data Kategori Baru')

@section('form_fields')
    <form id="addEngineForm" class="space-y-4">
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin <span class="text-red-500">*</span></label>
            <input type="text" id="name" required placeholder="Contoh: M54" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Konfigurasi Silinder <span class="text-red-500">*</span></label>
            <input type="text" id="cylinders" required placeholder="Contoh: Inline 6" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kapasitas Mesin (cc) <span class="text-red-500">*</span></label>
            <input type="number" id="engine_cap" required placeholder="Contoh: 2500" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kapasitas Oli (Liter) <span class="text-red-500">*</span></label>
            <input type="number" step="0.1" id="oil_cap" required placeholder="Contoh: 6.5" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Bahan Bakar <span class="text-red-500">*</span></label>
            <select id="fuel_type" required class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
                <option value="Bensin">Bensin</option>
                <option value="Diesel">Diesel</option>
            </select>
        </div>
    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('jenis-mesin.index'),
        'submitBtnText' => 'Simpan Data'
    ])

    <script>
        let isDirty = false;

        function formatNumberInput(e) {
            let value = e.target.value.replace(/[^\d]/g, "");
            if (value.length > 0) {
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            }
        }

        document.getElementById('engine_cap').addEventListener('input', formatNumberInput);
        document.getElementById('oil_cap').addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9,.]/g, '');
        });

        document.getElementById('addEngineForm').addEventListener('input', () => isDirty = true);

        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('access_token');

            let valOil = document.getElementById('oil_cap').value;
            const rawEngineCap = document.getElementById('engine_cap').value.replace(/\./g, '');
            const cleanOilCap = parseFloat(valOil.replace(',', '.'));
            
            const data = {
                name: document.getElementById('name').value,
                cylinders: document.getElementById('cylinders').value,
                engine_cap: Number(rawEngineCap),
                oil_cap: cleanOilCap,
                fuel_type: document.getElementById('fuel_type').value,
            };

            // Validasi sederhana
            if (!data.name || !data.engine_cap || !data.oil_cap) {
                Swal.fire('Oops!', 'Semua field wajib diisi brok!', 'warning');
                return;
            }

            try {
                Swal.fire({ title: 'Menyimpan data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

                const response = await fetch('/api/engine-types', {
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
                    isDirty = false;
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 2000, showConfirmButton: false });
                    window.location.href = "{{ route('jenis-mesin.index') }}";
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Cek lagi inputan lu!' });
                }
            } catch (error) {
                Swal.fire('Error', 'Koneksi server bermasalah.', 'error');
            }
        };

        window.addEventListener('beforeunload', (e) => {
            if (isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const btn = document.getElementById('submitBtnApi');
                if (btn) btn.click();
            }
        });
    </script>
@endsection