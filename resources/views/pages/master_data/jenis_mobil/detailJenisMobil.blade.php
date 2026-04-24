@extends('layouts.master')

@section('title', 'Detail Jenis Mobil')
@section('title_header', 'Master Data | Jenis Mobil')

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

@section('detail_title', 'Detail Informasi Mobil')

@section('detail_content')
    <div id="carDetail" class="space-y-6">
        <p class="text-gray-400 italic">Memuat informasi mobil...</p>
    </div>

    <script>
        async function fetchDetail() {
            const id = window.location.pathname.split('/').pop();
            const token = localStorage.getItem('access_token');
            const detailContainer = document.getElementById('carDetail');

            try {
                const res = await fetch(`/api/car-types/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const i = result.data;
                    const engineName = i.engine_type ? i.engine_type.name : '<span class="text-red-500 italic">Mesin belum diset</span>';

                    const detailHtml = `
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Kode Sasis</p><p class="font-bold text-bmw-dark">${i.chassis_number}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Nama Model</p><p class="font-bold text-bmw-dark">${i.name}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Seri</p><p class="font-bold text-bmw-dark">${i.series}</p></div>
                        <div class="flex pb-4 border-b border-gray-50">
                            <p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Daftar Kode Mesin</p>
                            <p class="flex-1 font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg inline-block w-fit">${i.engine_code || '-'}</p>
                        </div>
                    `;

                    detailContainer.innerHTML = detailHtml;

                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    if (i.created_at) {
                        document.querySelectorAll('.created-date-text').forEach(el => el.innerText = new Date(i.created_at).toLocaleDateString('id-ID', options));
                    }
                    if (i.updated_at) {
                        const dateStr = new Date(i.updated_at).toLocaleDateString('id-ID', options);
                        document.querySelectorAll('.updated-date-text').forEach(el => el.innerText = dateStr);
                    }
                } else {
                    detailContainer.innerHTML = `<p class="text-red-500 font-bold">Error: ${result.message || 'Gagal mengambil data'}</p>`;
                }
            } catch (e) {
                console.error(e);
                detailContainer.innerHTML = `<p class="text-red-500">Koneksi ke API bermasalah brok.</p>`;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchDetail();

            document.getElementById('btnHapusData').onclick = async () => {
                const id = window.location.pathname.split('/').pop();
                const token = localStorage.getItem('access_token');

                // Pop-up Konfirmasi
                const result = await Swal.fire({
                    title: 'Yakin mau hapus brok?',
                    text: "Data yang udah dihapus gak bakal balik lagi lho!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF4D4D',
                    cancelButtonColor: '#213F5C',
                    confirmButtonText: 'Ya, Hapus Saja!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    try {
                        Swal.fire({
                            title: 'Memproses...',
                            didOpen: () => { Swal.showLoading() }
                        });

                        const res = await fetch(`/api/car-types/${id}`, {
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
                                text: 'Data mobil berhasil dibuang dari sistem.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            window.location.href = "{{ route('jenis-mobil.index') }}";
                        } else {
                            Swal.fire('Gagal!', 'Waduh, datanya gagal diapus brok.', 'error');
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
        'backUrl' => route('jenis-mobil.index'),
        'editUrl' => route('jenis-mobil.edit', ['id' => request()->route('id')]),
        'hapusUrl' => route('jenis-mobil.delete', ['id' => request()->route('id')]),
        'sectionTitle' => 'Spesifikasi Mobil'
    ])
@endsection