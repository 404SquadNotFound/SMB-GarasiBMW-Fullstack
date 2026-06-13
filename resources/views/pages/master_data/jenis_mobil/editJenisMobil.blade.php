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
            <input type="text" id="engineSearch" placeholder="Ketik untuk cari mesin..." 
                class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] font-semibold text-[#213F5C] focus:border-orange-500 mb-2">
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

        let allEngines = [];

       async function initEditPage() {
            try {
                // 1. Load Semua Mesin
                const resEngines = await fetch('/api/engine-types?limit=200', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const resultEngines = await resEngines.json();
                allEngines = resultEngines.data || [];
                
                if (resEngines.ok) {
                    renderEngineOptions(allEngines);
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
                        const savedEngines = data.engine_code.split(',').map(s => s.trim());
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

        function renderEngineOptions(engines) {
            const selectedValues = Array.from(engineSelect.selectedOptions).map(o => o.value);
            engineSelect.innerHTML = '';
            engines.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.engine_type_id;
                opt.setAttribute('data-name', e.name);
                opt.text = `${e.name} (${e.engine_cap}cc)`;
                if (selectedValues.includes(String(e.engine_type_id))) opt.selected = true;
                engineSelect.appendChild(opt);
            });
        }

        document.getElementById('engineSearch').addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase();
            const filtered = q ? allEngines.filter(eng => 
                eng.name.toLowerCase().includes(q) || 
                String(eng.engine_cap).includes(q)
            ) : allEngines;
            renderEngineOptions(filtered);
        });

        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            
            // 1. Ambil value dan bersihkan spasi
            const chassisVal = document.getElementById('chassis_number').value.trim();
            const nameVal = document.getElementById('name').value.trim();
            const seriesVal = document.getElementById('series').value.trim();
            const selectedEngines = Array.from(engineSelect.selectedOptions).map(opt => opt.value);

            // 2. Kumpulkan field mandatory yang masih kosong
            let emptyFields = [];

            if (!chassisVal) emptyFields.push('Kode Sasis');
            if (!nameVal) emptyFields.push('Nama Model');
            if (!seriesVal) emptyFields.push('Seri');
            if (selectedEngines.length === 0) emptyFields.push('Jenis Mesin');

            // 3. Tampilkan Swal jika ada yang terlewat
            if (emptyFields.length > 0) {
                let errorMessage = emptyFields.join(', ') + ' tidak boleh kosong!';
                Swal.fire('Data Belum Lengkap!', errorMessage, 'warning');
                return;
            }

            // 4. Lanjut susun payload
            const updatedData = {
                chassis_number: chassisVal,
                name: nameVal,
                series: seriesVal,
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
                    Swal.fire('Peringatan!', err.message || 'Cek lagi inputannya.', 'warning');
                }
            } catch (error) { Swal.fire('Error!', 'Koneksi API putus.', 'error'); }
        };

        document.addEventListener('DOMContentLoaded', initEditPage);
    </script>
@endsection