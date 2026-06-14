@extends('layouts.master')

@section('title', 'Tambah Supplier')
@section('title_header', 'Master Data | Supplier')

@section('form_icon')
    <div
        class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Data Supplier Baru')

@section('form_fields')
    <div>
        <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Supplier <span
                class="text-red-500">*</span></label>
        <input type="text" id="name" placeholder="Masukkan nama supplier"
            class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px]">
    </div>
    <div>
        <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Deskripsi Supplier</label>
        <textarea id="description" rows="6" placeholder="Masukan deskripsi"
            class="w-full px-5 py-4 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] resize-none"></textarea>
    </div>
@endsection

@section('content')
    @include('layouts.form_wrapper', ['backUrl' => route('supplier.index'), 'submitBtnText' => 'Simpan Supplier'])
    
    <script>
        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault(); // Mencegah reload bawaan form (jika ada)

            // 1. Ambil value dan hapus spasi berlebih
            const nameVal = document.getElementById('name').value.trim();
            const descVal = document.getElementById('description').value.trim();

            // 2. Kumpulkan field yang wajib tapi masih kosong
            let emptyFields = [];
            
            if (!nameVal) emptyFields.push('Nama Supplier');

            // 3. Tampilkan peringatan jika ada yang kosong
            if (emptyFields.length > 0) {
                let errorMessage = emptyFields.join(', ') + ' tidak boleh kosong!';
                Swal.fire('Data Belum Lengkap!', errorMessage, 'warning');
                return; // Stop eksekusi API
            }

            // 4. Lanjut hit API jika aman
            const data = { 
                name: nameVal, 
                description: descVal 
            };
            
            const token = localStorage.getItem('access_token');
            
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            try {
                const res = await fetch('/api/suppliers', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
                    body: JSON.stringify(data)
                });

                if (res.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Supplier baru berhasil ditambahkan.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('supplier.index') }}";
                } else {
                    const err = await res.json();
                    Swal.fire('Peringatan!', err.message || 'Cek lagi inputannya.', 'warning');
                }
            } catch (error) {
                Swal.fire('Error!', 'Koneksi API terputus.', 'error');
            }
        };
    </script>
@endsection