@extends('layouts.master')

@section('title', 'Tambah Jenis Mobil')
@section('title_header', 'Master Data | Jenis Mobil')

@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Jenis Mobil Baru')

@section('form_fields')
    <div class="space-y-4">
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Sasis *</label>
            <input type="text" id="chassis_number" placeholder="E46" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB]">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Model *</label>
            <input type="text" id="name" placeholder="BMW E46 325i" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB]">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Seri *</label>
            <input type="text" id="series" placeholder="3 Series" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB]">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Pilih Jenis Mesin (Bisa pilih banyak) *</label>
            <p class="text-[11px] text-gray-400 mb-2 font-medium italic">Tahan tombol <b>Ctrl (Windows)</b> atau <b>Cmd (Mac)</b> buat milih lebih dari satu mesin brok.</p>
            <select id="engine_ids" multiple class="w-full px-4 py-3 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none min-h-40 focus:border-[#1273EB] scrollbar-hide">
                <option value="" disabled>-- Sedang memuat mesin... --</option>
            </select>
        </div>
    </div>

    <style>
        /* Styling biar option yang dipilih keliatan biru BMW */
        option:checked {
            background-color: #1273EB !important;
            color: white !important;
        }
        option {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            color: #213F5C;
        }
    </style>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('jenis-mobil.index'),
        'sectionTitle' => 'Informasi Jenis Mobil',
        'submitBtnText' => 'Simpan Data'
    ])

    <script>
        const token = localStorage.getItem('access_token');
        const engineSelect = document.getElementById('engine_ids');

        // 1. Ambil data Jenis Mesin buat Dropdown (Cuma satu fungsi aja)
        async function loadEngineTypes() {
            try {
                const res = await fetch('/api/engine-types', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
                const result = await res.json();
                
                if (res.ok) {
                    engineSelect.innerHTML = ''; // Kosongin loading
                    const engines = result.data.data || result.data; // Handle Laravel Paginate
                    
                    engines.forEach(engine => {
                        const option = document.createElement('option');
                        option.value = engine.engine_type_id;
                        option.text = `${engine.name} (${engine.engine_cap}cc)`;
                        engineSelect.appendChild(option);
                    });
                }
            } catch (e) { 
                console.error("Gagal muat mesin:", e);
                engineSelect.innerHTML = '<option disabled class="text-red-500">Gagal memuat data mesin</option>';
            }
        }

        // 2. Proses Simpan
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();

            // Ambil array ID yang dipilih di multiple select
            const selectedEngines = Array.from(engineSelect.selectedOptions).map(opt => opt.value);

            if (selectedEngines.length === 0) {
                Swal.fire('Eitss!', 'Pilih minimal satu mesin brok!', 'warning');
                return;
            }

            const data = {
                chassis_number: document.getElementById('chassis_number').value,
                name: document.getElementById('name').value,
                series: document.getElementById('series').value,
                engine_ids: selectedEngines // Kirim sebagai array
            };

            Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });

            try {
                const res = await fetch('/api/car-types', {
                    method: 'POST',
                    headers: { 
                        'Accept': 'application/json', 
                        'Content-Type': 'application/json', 
                        'Authorization': `Bearer ${token}` 
                    },
                    body: JSON.stringify(data)
                });

                if (res.ok) { 
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Mobil BMW baru siap digas.', timer: 1500, showConfirmButton: false });
                    window.location.href = "{{ route('jenis-mobil.index') }}"; 
                } else {
                    const err = await res.json();
                    Swal.fire('Gagal!', err.message || 'Cek inputan lu brok.', 'error');
                }
            } catch (error) {
                Swal.fire('Error!', 'Backend lu pingsan kali brok.', 'error');
            }
        };

        // Jalankan saat halaman siap
        document.addEventListener('DOMContentLoaded', loadEngineTypes);
    </script>
@endsection