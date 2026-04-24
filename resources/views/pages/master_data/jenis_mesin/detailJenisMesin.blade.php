@extends('layouts.master')

@section('title', 'Detail Jenis Mesin')
@section('title_header', 'Master Data | Jenis Mesin')

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

@section('detail_title', 'Detail Mesin')

@section('detail_content')
    <div class="space-y-6" id="engineDetailContainer">
        <p class="text-gray-400 italic">Memuat detail mesin...</p>
    </div>

    <script>
        async function fetchEngineDetail() {
            const token = localStorage.getItem('access_token');
            const pathArray = window.location.pathname.split('/');
            const id = pathArray[pathArray.length - 1];
            const container = document.getElementById('engineDetailContainer');

            try {
                const res = await fetch(`/api/engine-types/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const item = result.data;
                    const detailHtml = `
                        <div class="flex items-start border-b border-gray-50 pb-4">
                            <p class="w-64 text-[14px] font-medium text-gray-400 uppercase tracking-wide">Kode Mesin</p>
                            <p class="text-[14px] font-bold text-[#213F5C]">${item.name || '-'}</p>
                        </div>
                        <div class="flex items-start border-b border-gray-50 pb-4">
                            <p class="w-64 text-[14px] font-medium text-gray-400 uppercase tracking-wide">Konfigurasi Silinder</p>
                            <p class="text-[14px] font-bold text-[#213F5C]">${item.cylinders || '-'}</p>
                        </div>
                        <div class="flex items-start border-b border-gray-50 pb-4">
                            <p class="w-64 text-[14px] font-medium text-gray-400 uppercase tracking-wide">Kapasitas Mesin</p>
                            <p class="text-[14px] font-bold text-[#213F5C]">${item.engine_cap} cc</p>
                        </div>
                        <div class="flex items-start border-b border-gray-50 pb-4">
                            <p class="w-64 text-[14px] font-medium text-gray-400 uppercase tracking-wide">Kapasitas Oli</p>
                            <p class="text-[14px] font-bold text-[#213F5C]">${item.oil_cap} Liter</p>
                        </div>
                        <div class="flex items-start border-b border-gray-50 pb-4">
                            <p class="w-64 text-[14px] font-medium text-gray-400 uppercase tracking-wide">Bahan Bakar</p>
                            <p class="text-[14px] font-bold text-[#213F5C]">${item.fuel_type || '-'}</p>
                        </div>
                    `;
                    container.innerHTML = detailHtml;

                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    if (item.created_at) {
                        const dateStr = new Date(item.created_at).toLocaleDateString('id-ID', options);
                        document.querySelectorAll('.created-date-text').forEach(el => el.innerText = dateStr);
                    }
                    if (item.updated_at) {
                        const dateStr = new Date(item.updated_at).toLocaleDateString('id-ID', options);
                        document.querySelectorAll('.updated-date-text').forEach(el => el.innerText = dateStr);
                    }

                } else {
                    container.innerHTML = `<p class="text-red-500">Gagal memuat data: ${result.message}</p>`;
                }
            } catch (error) {
                console.error(error);
                container.innerHTML = `<p class="text-red-500">Terjadi kesalahan koneksi ke server.</p>`;
            }
        }

        // GABUNGIN SEMUA DISINI
        document.addEventListener('DOMContentLoaded', () => {
            fetchEngineDetail();

            document.getElementById('btnHapusData').onclick = async () => {
                const id = window.location.pathname.split('/').pop();
                const token = localStorage.getItem('access_token');

                const result = await Swal.fire({
                    title: 'Yakin mau hapus brok?',
                    text: "Kalo ada mobil pake mesin ini, datanya bakal error!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF4D4D',
                    cancelButtonColor: '#213F5C',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    try {
                        Swal.fire({
                            title: 'Memproses...',
                            didOpen: () => { Swal.showLoading() }
                        });

                        const res = await fetch(`/api/engine-types/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        if (res.ok) {
                            await Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data mesin berhasil dibuang.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // REDIRECT KE INDEX MESIN, BUKAN MOBIL
                            window.location.href = "{{ route('jenis-mesin.index') }}";
                        } else {
                            const errorData = await res.json();
                            Swal.fire('Gagal!', errorData.message || 'Gagal hapus data.', 'error');
                        }
                    } catch (e) {
                        console.error(e);
                        Swal.fire('Error!', 'Koneksi ke API bermasalah.', 'error');
                    }
                }
            };
        });
    </script>
@endsection

@section('content')
    @include('layouts.detail_wrapper', [
        'backUrl' => route('jenis-mesin.index'),
        'editUrl' => route('jenis-mesin.edit', ['id' => request()->route('id')]),
        'hapusUrl' => route('jenis-mesin.delete', ['id' => request()->route('id')]),
        'sectionTitle' => 'Detail Informasi Mesin'
    ])
@endsection