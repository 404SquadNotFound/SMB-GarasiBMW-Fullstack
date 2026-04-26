@extends('layouts.master')

@section('title', 'Edit Jenis Mesin')
@section('title_header', 'Master Data | Jenis Mesin')

@section('form_icon')
    <div class="w-12 h-12 bg-[#FFA500] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-orange-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Mengubah Data Jenis Mesin')

@section('form_fields')
    <form id="editEngineForm" class="space-y-4">
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
            <input type="text" id="engine_cap" required placeholder="Contoh: 2.500" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kapasitas Oli (Liter) <span class="text-red-500">*</span></label>
            <input type="text" id="oil_cap" required placeholder="Contoh: 6,5" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
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
        'sectionTitle' => 'Edit Informasi Mesin',
        'submitBtnText' => 'Update Data'
    ])

    <script>
        let isDirty = false;
        const pathArray = window.location.pathname.split('/');
        const engineId = pathArray[pathArray.length - 1];
        const token = localStorage.getItem('access_token');

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

        async function loadEngineData() {
            try {
                const response = await fetch(`/api/engine-types/${engineId}`, {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await response.json();
                if (response.ok) {
                    const data = result.data;
                    document.getElementById('name').value = data.name;
                    document.getElementById('cylinders').value = data.cylinders;
                    document.getElementById('engine_cap').value = new Intl.NumberFormat('id-ID').format(data.engine_cap);
                    document.getElementById('oil_cap').value = data.oil_cap;
                    document.getElementById('fuel_type').value = data.fuel_type;
                    document.getElementById('editEngineForm').addEventListener('input', () => isDirty = true);
                }
            } catch (error) { console.error('Error:', error); }
        }

        document.addEventListener('DOMContentLoaded', loadEngineData);

        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            
            const rawEngineCap = document.getElementById('engine_cap').value.replace(/\./g, '');
            const rawOilCap = document.getElementById('oil_cap').value.replace(',', '.');
            
            const updatedData = {
                name: document.getElementById('name').value,
                cylinders: document.getElementById('cylinders').value,
                engine_cap: Number(rawEngineCap),
                oil_cap: parseFloat(rawOilCap),
                fuel_type: document.getElementById('fuel_type').value,
            };

            try {
                Swal.fire({ title: 'Memperbarui data...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

                const response = await fetch(`/api/engine-types/${engineId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(updatedData)
                });

                if (response.ok) {
                    isDirty = false;
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data mesin sudah diperbarui.', timer: 2000, showConfirmButton: false });
                    window.location.href = "{{ route('jenis-mesin.index') }}";
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Cek lagi inputannya brok!' });
                }
            } catch (error) {
                Swal.fire('Error', 'Koneksi API bermasalah.', 'error');
            }
        };

        window.addEventListener('beforeunload', (e) => {
            if (isDirty) { e.preventDefault(); e.returnValue = ''; }
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