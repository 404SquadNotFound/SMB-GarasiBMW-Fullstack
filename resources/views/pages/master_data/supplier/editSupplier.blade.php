@extends('layouts.master')

@section('title', 'Edit Supplier')
@section('title_header', 'Master Data | Supplier')

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
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Supplier *</label>
            <input type="text" id="name" class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-orange-500 transition-all font-semibold text-[#213F5C]">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Deskripsi Supplier</label>
            <textarea id="description" rows="6" class="w-full px-5 py-4 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-orange-500 transition-all font-semibold text-[#213F5C] resize-none"></textarea>
        </div>
    </div>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('supplier.index'), 
        'sectionTitle' => 'Edit Informasi Supplier',
        'submitBtnText' => 'Update Data Supplier'
    ])

    <script>
        const id = window.location.pathname.split('/').pop();
        const token = localStorage.getItem('access_token');

        async function loadData() {
            try {
                const res = await fetch(`/api/suppliers/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (res.ok) {
                    document.getElementById('name').value = result.data.name;
                    document.getElementById('description').value = result.data.description || '';
                }
            } catch (e) { console.error(e); }
        }

        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const data = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value
            };

            Swal.fire({ title: 'Menyimpan...', didOpen: () => Swal.showLoading() });

            try {
                const res = await fetch(`/api/suppliers/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'Accept': 'application/json', 
                        'Content-Type': 'application/json', 
                        'Authorization': `Bearer ${token}` 
                    },
                    body: JSON.stringify(data)
                });

                if (res.ok) {
                    await Swal.fire({ icon: 'success', title: 'Updated!', text: 'Data supplier berhasil diubah.', timer: 1500, showConfirmButton: false });
                    window.location.href = "{{ route('supplier.index') }}";
                } else {
                    const err = await res.json();
                    Swal.fire('Gagal!', err.message || 'Cek inputan.', 'error');
                }
            } catch (e) { Swal.fire('Error!', 'API Offline.', 'error'); }
        };

        document.addEventListener('DOMContentLoaded', loadData);
    </script>
@endsection