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
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('access_token');
            
            const data = {
                name: document.getElementById('name').value,
                cylinders: document.getElementById('cylinders').value,
                engine_cap: Number(document.getElementById('engine_cap').value),
                oil_cap: parseFloat(document.getElementById('oil_cap').value),
                fuel_type: document.getElementById('fuel_type').value,
            };

            try {
                // Tampilkan Loading
                Swal.fire({
                    title: 'Menyimpan data...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

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
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Jenis mesin baru berhasil disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('jenis-mesin.index') }}";
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