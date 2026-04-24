@extends('layouts.master')

@section('title', 'Detail Supplier')
@section('title_header', 'Master Data | Supplier')

@section('detail_icon')
    <div
        class="w-12 h-12 bg-white border border-[#E5E9F2] rounded-[15px] flex items-center justify-center text-[#213F5C] shadow-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z">
            </path>
        </svg>
    </div>
@endsection

@section('detail_title', 'Detail Supplier')

@section('detail_content')
    <div id="supplierDetail" class="space-y-6">Memuat...</div>

    <script>
        const id = window.location.pathname.split('/').pop();
        const token = localStorage.getItem('access_token');

        async function fetchDetail() {
            const res = await fetch(`/api/suppliers/${id}`, {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const result = await res.json();
            if (res.ok) {
                const i = result.data;
                document.getElementById('supplierDetail').innerHTML = `
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400">Nama Supplier</p><p class="font-bold text-[#213F5C]">${i.name}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400">Deskripsi</p><p class="flex-1 font-bold text-[#213F5C]">${i.description || '-'}</p></div>`;
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                if (i.created_at) {
                    document.querySelectorAll('.created-date-text').forEach(el => el.innerText = new Date(i.created_at).toLocaleDateString('id-ID', options));
                }
                if (i.updated_at) {
                    const dateStr = new Date(i.updated_at).toLocaleDateString('id-ID', options);
                    document.querySelectorAll('.updated-date-text').forEach(el => el.innerText = dateStr);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchDetail();
            document.getElementById('btnHapusData').onclick = async () => {
                const confirm = await Swal.fire({
                    title: 'Hapus Supplier?',
                    text: "Data supplier ini bakal ilang permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF4D4D',
                    confirmButtonText: 'Ya, Hapus!'
                });
                if (confirm.isConfirmed) {
                    const res = await fetch(`/api/suppliers/${id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${token}` }
                    });
                    if (res.ok) {
                        await Swal.fire('Terhapus!', 'Supplier berhasil dihapus.', 'success');
                        window.location.href = "{{ route('supplier.index') }}";
                    }
                }
            };
        });
    </script>
@endsection

@section('content')
    @include('layouts.detail_wrapper', [
        'backUrl' => route('supplier.index'),
        'editUrl' => "/supplier/edit/" . request()->route('id'),
        'sectionTitle' => 'Informasi Supplier'
    ])
@endsection