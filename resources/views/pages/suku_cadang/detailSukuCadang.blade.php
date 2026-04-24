@extends('layouts.master')

@section('title', 'Detail Suku Cadang')
@section('title_header', 'Master Data | Suku Cadang')

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

@section('detail_title', 'Detail Suku Cadang')

@section('detail_content')
    <div id="sparePartDetailContainer" class="space-y-6">
        <p class="text-gray-400 italic">Memuat detail suku cadang...</p>
    </div>

    <script>
        async function fetchSparePartDetail() {
            const token = localStorage.getItem('access_token');
            const pathArray = window.location.pathname.split('/');
            const id = pathArray[pathArray.length - 1];
            const container = document.getElementById('sparePartDetailContainer');

            try {
                const res = await fetch(`/api/spareparts/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const item = result.data ?? result;

                    const formatRupiah = (num) =>
                        num != null
                            ? 'Rp ' + Number(num).toLocaleString('id-ID')
                            : '-';

                    const detailHtml = `
                        {{-- Seksi: Informasi Suku Cadang --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                                </svg>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Suku Cadang</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Kode Barang</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.item_code || '-'}</p>
                                </div>
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Nama Barang</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.name || '-'}</p>
                                </div>
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Kategori</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.category?.name ?? item.category ?? '-'}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Seksi: Informasi Mobil Suku Cadang --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3M8 17l4 4m0 0l4-4m-4 4V11" />
                                </svg>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Mobil Suku Cadang</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Tipe Mobil</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.car_type?.name ? (item.car_type.chassis_number + ' - ' + item.car_type.name) : '-'}</p>
                                </div>
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Kode Mesin</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.car_type?.engine_code || '-'}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Seksi: Harga dan Stok --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 17H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3M8 17l4 4m0 0l4-4m-4 4V11" />
                                </svg>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Harga dan Stok</h3>
                            </div>

                            {{-- Card ringkasan harga --}}
                            <div class="bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl p-5 flex justify-between items-start">
                                <div class="space-y-1.5">
                                    <p class="text-[13px] text-gray-400">
                                        HPP: <span class="font-bold text-[#213F5C] text-[15px]">${formatRupiah(item.cost_off_sell)}</span>
                                    </p>
                                    <p class="text-[13px] text-gray-400">
                                        Harga Jual: <span class="font-bold text-[#1273EB] text-[15px]">${formatRupiah(item.selling_price)}</span>
                                    </p>
                                    <p class="text-[13px] text-gray-400">
                                        Stok: <span class="font-bold text-[#213F5C] text-[15px]">${item.quantity ?? '-'} Barang</span>
                                    </p>
                                </div>
                                <div class="text-right space-y-1.5">
                                    <p class="text-[13px] text-gray-400">
                                        Tanggal: <span class="font-semibold text-[#213F5C]">${item.date
                                            ? new Date(item.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
                                            : '-'
                                        }</span>
                                    </p>
                                    <p class="text-[13px] text-gray-400">
                                        Supplier: <span class="font-semibold text-[#213F5C]">${item.supplier?.name || item.supplier_id || '-'}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;

                    container.innerHTML = detailHtml;

                    // Update timestamp di Quick Info sidebar
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

        document.addEventListener('DOMContentLoaded', () => {
            fetchSparePartDetail();

            document.getElementById('btnHapusData').onclick = async () => {
                const id = window.location.pathname.split('/').pop();
                const token = localStorage.getItem('access_token');

                const result = await Swal.fire({
                    title: 'Yakin mau hapus brok?',
                    text: 'Data suku cadang ini akan dihapus permanen!',
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

                        const res = await fetch(`/api/spareparts/${id}`, {
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
                                text: 'Data suku cadang berhasil dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            window.location.href = "{{ route('suku-cadang.index') }}";
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
        'backUrl' => route('suku-cadang.index'),
        'editUrl' => route('suku-cadang.edit', ['id' => request()->route('id')]),
        'hapusUrl' => route('suku-cadang.delete', ['id' => request()->route('id')]),
        'sectionTitle' => 'Detail Suku Cadang'
    ])
@endsection