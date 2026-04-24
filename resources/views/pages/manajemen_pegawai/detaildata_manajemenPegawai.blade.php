@extends('layouts.master')

@section('title', 'Detail Pegawai')
@section('title_header', 'Manajemen Pegawai')

@section('detail_icon')
    <div class="w-12 h-12 bg-white border border-[#E5E9F2] rounded-[15px] flex items-center justify-center text-[#213F5C] shadow-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
        </svg>
    </div>
@endsection

@section('detail_title', 'Detail Karyawan')

@section('detail_content')
    <div id="pegawaiDetailContainer" class="space-y-4">
        <p class="text-gray-400 italic text-[13px]">Memuat detail pegawai...</p>
    </div>

    <script>
        async function fetchPegawaiDetail() {
            const token = localStorage.getItem('access_token');
            const pathArray = window.location.pathname.split('/');
            const id = pathArray[pathArray.length - 1];
            const container = document.getElementById('pegawaiDetailContainer');

            try {
                const res = await fetch(`/api/employees/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const d = result.data ?? result;

                    const formatRupiah = (num) =>
                        num != null
                            ? 'Rp. ' + Number(num).toLocaleString('id-ID', { minimumFractionDigits: 2 })
                            : '-';

                    const formatTanggal = (str) =>
                        str ? new Date(str).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';


                    const detailHtml = `
                        {{-- Seksi: Informasi Pribadi --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-5 h-5 rounded-full bg-[#1273EB] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Pribadi Karyawan</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-5">
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Nama Lengkap</p>
                                    <p class="text-[14px] font-semibold text-[#213F5C]">${d.name || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Tanggal Bergabung</p>
                                    <p class="text-[14px] font-semibold text-[#213F5C]">${formatTanggal(d.join_date)}</p>
                                </div>
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Tanggal Lahir</p>
                                    <p class="text-[14px] font-semibold text-[#213F5C]">${formatTanggal(d.birth_date)}</p>
                                </div>
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Status</p>
                                    ${(d.status == 1 || d.status === true)
                                        ? '<span class="inline-block px-3 py-1 bg-green-50 text-green-600 text-[12px] font-semibold rounded-lg">Aktif</span>'
                                        : '<span class="inline-block px-3 py-1 bg-red-50 text-red-500 text-[12px] font-semibold rounded-lg">Non-Aktif</span>'}
                                </div>
                                ${d.address ? `
                                <div class="col-span-2">
                                    <p class="text-[12px] text-gray-400 mb-1">Alamat</p>
                                    <p class="text-[14px] font-semibold text-[#213F5C]">${d.address}</p>
                                </div>` : ''}
                            </div>
                        </div>

                        {{-- Seksi: Informasi Akun --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-5 h-5 rounded-full bg-[#1273EB] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Akun Karyawan</h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Email</p>
                                    <p class="text-[14px] font-semibold text-[#213F5C]">${d.email || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Password</p>
                                    <p class="text-[14px] font-semibold text-[#213F5C] tracking-widest">••••••••••••••••</p>
                                </div>
                                <div>
                                    <p class="text-[12px] text-gray-400 mb-1">Role</p>
                                    <span class="inline-block px-3 py-1 bg-blue-50 text-[#1273EB] text-[12px] font-semibold rounded-lg">
                                        ${d.role || '-'}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Seksi: Pemasukan --}}
                        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-5 h-5 rounded-full bg-[#1273EB] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-[15px] font-bold text-[#213F5C]">Pemasukan Tetap</h3>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <p class="text-[13px] text-gray-400">Gaji Pokok</p>
                                <p class="text-[14px] font-bold text-[#213F5C]">${formatRupiah(d.base_salary)}</p>
                            </div>
                        </div>
                    `;

                    container.innerHTML = detailHtml;

                    // Update timestamp di sidebar Quick Info
                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    if (d.created_at) {
                        const dateStr = new Date(d.created_at).toLocaleDateString('id-ID', options);
                        document.querySelectorAll('.created-date-text').forEach(el => el.innerText = dateStr);
                    }
                    if (d.updated_at) {
                        const dateStr = new Date(d.updated_at).toLocaleDateString('id-ID', options);
                        document.querySelectorAll('.updated-date-text').forEach(el => el.innerText = dateStr);
                    }

                } else {
                    container.innerHTML = `<p class="text-red-500 text-[13px]">Gagal memuat data: ${result.message}</p>`;
                }
            } catch (error) {
                console.error(error);
                container.innerHTML = `<p class="text-red-500 text-[13px]">Terjadi kesalahan koneksi ke server.</p>`;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchPegawaiDetail();

            document.getElementById('btnHapusData').onclick = async () => {
                const id = window.location.pathname.split('/').pop();
                const token = localStorage.getItem('access_token');

                const result = await Swal.fire({
                    title: 'Hapus Pegawai?',
                    text: 'Data pegawai ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#213F5C',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    try {
                        Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading(); } });

                        const res = await fetch(`/api/employees/${id}`, {
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
                                text: 'Data pegawai berhasil dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            window.location.href = "{{ route('manajemen-pegawai.index') }}";
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
        'backUrl'      => route('manajemen-pegawai.index'),
        'editUrl'      => route('manajemen-pegawai.edit', ['id' => request()->route('id')]),
        'hapusUrl'     => route('manajemen-pegawai.delete', ['id' => request()->route('id')]),
        'sectionTitle' => 'Detail Karyawan'
    ])
@endsection