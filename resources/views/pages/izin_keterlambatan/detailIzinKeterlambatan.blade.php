@extends('layouts.master')

@section('title', 'Detail Pendataan Izin')
@section('title_header', 'Kepegawaian | Pendataan Izin')

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

@section('detail_title', 'Detail Pendataan Izin')

@section('detail_content')
    <div id="izinDetailContainer" class="space-y-6">
        <p class="text-gray-400 italic">Memuat detail izin keterlambatan...</p>
    </div>

    <script>
        async function fetchIzinDetail() {
            const token      = localStorage.getItem('access_token');
            const pathArray  = window.location.pathname.split('/');
            const id         = pathArray[pathArray.length - 1];
            const container  = document.getElementById('izinDetailContainer');

            try {
                const res = await fetch(`/api/late-permits/${id}`, {
                    headers: {
                        'Accept'        : 'application/json',
                        'Authorization' : `Bearer ${token}`
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const item = result.data ?? result;

                    const formatDate = (dateStr) =>
                        dateStr
                            ? new Date(dateStr).toLocaleDateString('id-ID', {
                                day: 'numeric', month: 'long', year: 'numeric'
                              })
                            : '-';

                    const formatDateTime = (dateStr) =>
                        dateStr
                            ? new Date(dateStr).toLocaleDateString('id-ID', {
                                year: 'numeric', month: 'long', day: 'numeric',
                                hour: '2-digit', minute: '2-digit'
                              })
                            : '-';

                    const detailHtml = `
                        {{-- Seksi: Informasi Karyawan --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Karyawan</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Nama Karyawan</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.employee?.name ?? item.employee_id ?? '-'}</p>
                                </div>
                                <div class="flex items-center">
                                    <p class="w-40 text-[13px] text-gray-400">Jabatan</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C]">${item.employee?.position ?? '-'}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Seksi: Informasi Izin --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Izin</h3>
                            </div>

                            {{-- Card ringkasan izin --}}
                            <div class="bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl p-5 space-y-3">
                                <div class="flex items-start justify-between">
                                    <div class="space-y-2">
                                        <p class="text-[13px] text-gray-400">
                                            Tanggal Terlambat:
                                            <span class="font-bold text-[#213F5C] text-[15px]">${formatDate(item.late_date)}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-[#E5E9F2]">
                                    <p class="text-[13px] text-gray-400 mb-1">Alasan</p>
                                    <p class="text-[13px] font-semibold text-[#213F5C] leading-relaxed">
                                        ${item.reason ? item.reason : '<span class="italic text-gray-400">Tidak ada alasan yang dicantumkan.</span>'}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;

                    container.innerHTML = detailHtml;

                    // Update timestamp di Quick Info sidebar
                    if (item.created_at) {
                        document.querySelectorAll('.created-date-text').forEach(
                            el => el.innerText = formatDateTime(item.created_at)
                        );
                    }
                    if (item.updated_at) {
                        document.querySelectorAll('.updated-date-text').forEach(
                            el => el.innerText = formatDateTime(item.updated_at)
                        );
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
            fetchIzinDetail();

            document.getElementById('btnHapusData').onclick = async () => {
                const id    = window.location.pathname.split('/').pop();
                const token = localStorage.getItem('access_token');

                const confirm = await Swal.fire({
                    title             : 'Yakin mau hapus?',
                    text              : 'Data izin keterlambatan ini akan dihapus permanen!',
                    icon              : 'warning',
                    showCancelButton  : true,
                    confirmButtonColor: '#FF4D4D',
                    cancelButtonColor : '#213F5C',
                    confirmButtonText : 'Ya, Hapus!',
                    cancelButtonText  : 'Batal',
                    reverseButtons    : true
                });

                if (confirm.isConfirmed) {
                    try {
                        Swal.fire({
                            title  : 'Memproses...',
                            didOpen: () => { Swal.showLoading(); }
                        });

                        const res = await fetch(`/api/late-permits/${id}`, {
                            method : 'DELETE',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept'       : 'application/json'
                            }
                        });

                        if (res.ok) {
                            await Swal.fire({
                                icon              : 'success',
                                title             : 'Terhapus!',
                                text              : 'Data izin keterlambatan berhasil dihapus.',
                                timer             : 2000,
                                showConfirmButton : false
                            });
                            window.location.href = "{{ route('pendataan-izin.index') }}";
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
        'backUrl'      => route('izin-terlambat.index'),
        'editUrl'      => route('izin-terlambat.edit',   ['id' => request()->route('id')]),
        'hapusUrl'     => route('izin-terlambat.delete', ['id' => request()->route('id')]),
        'sectionTitle' => 'Detail Pendataan Izin'
    ])
@endsection