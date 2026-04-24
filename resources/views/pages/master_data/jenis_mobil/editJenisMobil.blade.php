@extends('layouts.master')

@section('title', 'Edit Jenis Mobil')
@section('title_header', 'Master Data | Jenis Mobil')

@section('form_icon')
    <div class="w-12 h-12 bg-orange-500 rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-orange-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
    </div>
@endsection

@section('form_fields')
    <div class="space-y-4">
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Sasis *</label>
            <input type="text" id="chassis_number" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-orange-500">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Model *</label>
            <input type="text" id="name" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-orange-500">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Seri *</label>
            <input type="text" id="series" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-orange-500">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Pilih Jenis Mesin (Bisa pilih banyak) *</label>
            <p class="text-[11px] text-gray-400 mb-2 font-medium italic">Tahan tombol <b>Ctrl</b> atau <b>Cmd</b> buat milih banyak brok.</p>
            <select id="engine_ids" multiple class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none min-h-40 focus:border-orange-500">
                </select>
        </div>
    </div>

    <style>
        option:checked { background-color: #f97316 !important; color: white !important; }
        option { padding: 10px 15px; font-weight: 600; color: #213F5C; border-bottom: 1px solid #f8fafc; }
    </style>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('jenis-mobil.index'), 
        'sectionTitle' => 'Edit Informasi Jenis Mobil',
        'submitBtnText' => 'Update Data Mobil'
    ])

    <script>
        const carId = window.location.pathname.split('/').pop();
        const token = localStorage.getItem('access_token');
        const engineSelect = document.getElementById('engine_ids');

       async function initEditPage() {
            try {
                // 1. Load Semua Mesin
                const resEngines = await fetch('/api/engine-types', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const resultEngines = await resEngines.json();
                const engines = resultEngines.data.data || resultEngines.data;
                
                if (resEngines.ok) {
                    engineSelect.innerHTML = '';
                    engines.forEach(e => {
                        engineSelect.innerHTML += `<option value="${e.engine_type_id}" data-name="${e.name}">${e.name} (${e.engine_cap}cc)</option>`;
                    });
                }

                // 2. Load Data Mobil & Set Value
                const resCar = await fetch(`/api/car-types/${carId}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const resultCar = await resCar.json();

                if (resCar.ok) {
                    const data = resultCar.data;
                    document.getElementById('chassis_number').value = data.chassis_number;
                    document.getElementById('name').value = data.name;
                    document.getElementById('series').value = data.series;
                    
                    // --- LOGIC SELECT BANYAK MESIN  ---
                    if (data.engine_code) {
                        // Pecah string "M54, M55" jadi array ["M54", "M55"]
                        const savedEngines = data.engine_code.split(',').map(s => s.trim());

                        // Looping semua option di select
                        for (let option of engineSelect.options) {
                            const engineName = option.getAttribute('data-name');
                            if (savedEngines.includes(engineName)) {
                                option.selected = true;
                            }
                        }
                    }
                }
            } catch (error) { console.error(error); }
        }

        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const selectedEngines = Array.from(engineSelect.selectedOptions).map(opt => opt.value);

            const updatedData = {
                chassis_number: document.getElementById('chassis_number').value,
                name: document.getElementById('name').value,
                series: document.getElementById('series').value,
                engine_ids: selectedEngines // Kirim array ID
            };

            Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });

            try {
                const res = await fetch(`/api/car-types/${carId}`, {
                    method: 'PUT',
                    headers: { 
                        'Accept': 'application/json', 
                        'Content-Type': 'application/json', 
                        'Authorization': `Bearer ${token}` 
                    },
                    body: JSON.stringify(updatedData)
                });

                if (res.ok) {
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data mobil BMW sudah diupdate.', timer: 1500, showConfirmButton: false });
                    window.location.href = "{{ route('jenis-mobil.index') }}";
                } else {
                    const err = await res.json();
                    Swal.fire('Gagal!', err.message || 'Cek lagi inputannya.', 'error');
                }
            } catch (error) { Swal.fire('Error!', 'Koneksi API putus brok.', 'error'); }
        };

        document.addEventListener('DOMContentLoaded', initEditPage);
    </script>
@endsection