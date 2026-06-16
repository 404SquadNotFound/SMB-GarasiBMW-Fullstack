{{-- resources/views/layanan-servis/riwayat-transaksi/show.blade.php --}}
@extends('layouts.master')

@section('title', 'Detail Riwayat Transaksi')
@section('title_header', 'Riwayat Transaksi | Detail')

@section('detail_icon')
    <div
        class="w-12 h-12 bg-white border border-[#E5E9F2] rounded-[15px] flex items-center justify-center text-[#213F5C] shadow-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
        </svg>
    </div>
@endsection

@section('detail_title', 'Detail Riwayat Transaksi')

@section('detail_content')
    <div id="transaksiDetail" class="space-y-6">
        <p class="text-gray-400 italic">Memuat informasi transaksi...</p>
    </div>

    <script>
        const paymentCfg = {
            'lunas'        : { label: 'Lunas',       bg: 'bg-[#EDFBF3]', text: 'text-[#16A34A]', border: 'border-[#A7F3D0]' },
            'paid'         : { label: 'Lunas',       bg: 'bg-[#EDFBF3]', text: 'text-[#16A34A]', border: 'border-[#A7F3D0]' },
            'down_payment' : { label: 'DP',          bg: 'bg-[#FFF8EC]', text: 'text-[#F59E0B]', border: 'border-[#FDE68A]' },
            'dp'           : { label: 'DP',          bg: 'bg-[#FFF8EC]', text: 'text-[#F59E0B]', border: 'border-[#FDE68A]' },
            'belum_lunas'  : { label: 'Belum Lunas', bg: 'bg-[#FFF5F5]', text: 'text-[#FF4D4D]', border: 'border-[#FFE0E0]' },
            'unpaid'       : { label: 'Belum Lunas', bg: 'bg-[#FFF5F5]', text: 'text-[#FF4D4D]', border: 'border-[#FFE0E0]' },
        };

        function getPaymentCfg(raw) {
            const key = String(raw || '').toLowerCase();
            return paymentCfg[key] ?? paymentCfg['belum_lunas'];
        }

        function formatRupiah(num) {
            if (num === null || num === undefined || num === '') return 'Rp 0';
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        async function fetchDetail() {
            const id = window.location.pathname.split('/').pop();
            const token = localStorage.getItem('access_token');
            const detailContainer = document.getElementById('transaksiDetail');

            try {
                const res = await fetch(`/api/transactions/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const result = await res.json();

                if (res.ok) {
                    const t = result.data;
                    const customer = t.vehicle?.customer ?? {};
                    const vehicle  = t.vehicle ?? {};

                    const rawPayment = t.status_payment ?? t.payment_status ?? 'unpaid';
                    const pc = getPaymentCfg(rawPayment);

                    const detailHtml = `
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Nama Pelanggan</p><p class="font-bold text-bmw-dark">${customer.name || '-'}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Nomor Telepon</p><p class="font-bold text-bmw-dark">${customer.phone_number || '-'}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Nomor Polisi</p><p class="font-bold text-bmw-dark">${vehicle.license_plate || '-'}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Model Mobil</p><p class="font-bold text-bmw-dark">${vehicle.model || '-'}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Tanggal Masuk</p><p class="font-bold text-bmw-dark">${formatTanggal(t.created_at)}</p></div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Status Pengerjaan</p>
                            <p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border bg-[#EDFBF3] text-[#16A34A] border-[#A7F3D0]">
                                    Selesai
                                </span>
                            </p>
                        </div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Status Pembayaran</p>
                            <p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold border ${pc.bg} ${pc.text} ${pc.border}">
                                    ${pc.label}
                                </span>
                            </p>
                        </div>
                        <div class="flex pb-4 border-b border-gray-50"><p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Total Biaya</p><p class="font-bold text-bmw-dark">${formatRupiah(t.total_amount)}</p></div>
                        <div class="flex pb-4 border-b border-gray-50">
                            <p class="w-64 text-gray-400 font-medium uppercase text-[12px] tracking-wider">Catatan</p>
                            <p class="flex-1 font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg inline-block w-fit">${t.notes || '-'}</p>
                        </div>
                    `;

                    detailContainer.innerHTML = detailHtml;

                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    if (t.created_at) {
                        document.querySelectorAll('.created-date-text').forEach(el => el.innerText = new Date(t.created_at).toLocaleDateString('id-ID', options));
                    }
                    if (t.updated_at) {
                        const dateStr = new Date(t.updated_at).toLocaleDateString('id-ID', options);
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
        });
    </script>
@endsection

@section('content')
    @include('layouts.detail_wrapper', [
        'backUrl' => route('riwayat-transaksi.index'),
        'sectionTitle' => 'Informasi Transaksi'
    ])
@endsection