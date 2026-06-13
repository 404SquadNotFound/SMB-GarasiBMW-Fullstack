@extends('layouts.master')

@section('title', 'Tambah Kategori Sparepart')
@section('title_header', 'Master Data | Kategori Sparepart')

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
    <div>
        <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Kategori <span
                class="text-red-500">*</span></label>
        <input type="text" id="name" placeholder="Contoh: Rem"
            class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none">
    </div>
    <div>
        <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Deskripsi</label>
        <textarea id="descriptions" rows="6" placeholder="Masukan Deskripsi"
            class="w-full px-5 py-4 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none resize-none"></textarea>
    </div>
@endsection

@section('content')
    @include('layouts.form_wrapper', ['backUrl' => route('kategori-sparepart.index'), 'submitBtnText' => 'Simpan Kategori'])

    <script>
        document.getElementById('submitBtnApi').onclick = async () => {
            // 1. Ambil value dan trim spasi kosong
            const nameVal = document.getElementById('name').value.trim();
            const descVal = document.getElementById('descriptions').value.trim();

            // 2. Kumpulkan field yang wajib diisi tapi masih kosong
            let emptyFields = [];
            
            if (!nameVal) emptyFields.push('Nama Kategori');

            // 3. Tampilkan Swal jika array emptyFields ada isinya
            if (emptyFields.length > 0) {
                let errorMessage = emptyFields.join(', ') + ' tidak boleh kosong!';
                Swal.fire('Data Belum Lengkap!', errorMessage, 'warning');
                return; // Hentikan eksekusi kode di bawahnya
            }

            // 4. Lanjut proses API jika data lengkap
            const employeeId = localStorage.getItem('user_id') || 1;
            const token = localStorage.getItem('access_token');

            const data = {
                name: nameVal,
                descriptions: descVal,
                employee_id: employeeId
            };

            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const res = await fetch('/api/item-categories', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();

                if (res.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kategori baru ditambahin.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('kategori-sparepart.index') }}";
                } else {
                    console.log(result.errors);
                    Swal.fire('Peringatan!', result.message || 'Cek inputan lagi.', 'warning');
                }
            } catch (error) {
                Swal.fire('Error!', 'Koneksi API terputus.', 'error');
            }
        };
    </script>
@endsection