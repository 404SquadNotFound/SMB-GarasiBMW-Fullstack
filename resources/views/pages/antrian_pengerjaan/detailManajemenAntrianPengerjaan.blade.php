{{-- resources/views/pages/antrian_pengerjaan/detailManajemenAntrianPengerjaan.blade.php --}}
{{--
TODO Backend:
- Endpoint ubah status pengerjaan : PUT/PATCH /api/transactions/{id}/status
- Endpoint proses pembayaran : POST /api/transactions/{id}/payment
body: { jasa_list:[{nama,biaya}], metode_pembayaran, total_jasa, total_suku_cadang, total_all }
- Endpoint hapus : DELETE /api/transactions/{id}
- Status pembayaran HANYA BACA — diubah via modal Proses Pembayaran di halaman ini
--}}
@extends('layouts.master')

@section('title', 'Detail Antrian Pengerjaan')
@section('title_header', 'Antrian Pengerjaan')

@section('content')
    @include('layouts.detail_wrapper_antrian')

    {{-- ════════════════════════════════════════════════════════════════════════════
    MODAL PROSES PEMBAYARAN
    Ditampilkan di atas halaman detail (z-index tinggi) saat btnProsesPembayaran diklik.
    Setelah "Cetak Nota" → redirect ke halaman previewNota.blade.php
    ════════════════════════════════════════════════════════════════════════════ --}}

    <style>
        /* ── Animasi modal ── */
        #modalPembayaran {
            animation: mpFadeIn 0.2s ease;
        }

        @keyframes mpFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        #modalPembayaran .mp-panel {
            animation: mpSlideUp 0.22s ease;
        }

        @keyframes mpSlideUp {
            from { transform: translateY(28px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* ── Kartu metode pembayaran ── */
        .mp-metode-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border: 1.5px solid #E5E9F2;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.15s ease;
            background: #fff;
            font-size: 13px;
            font-weight: 600;
            color: #213F5C;
            user-select: none;
        }
        .mp-metode-card:hover         { border-color: #1273EB; background: #F0F7FF; }
        .mp-metode-card.selected      { border-color: #1273EB; background: #EAF2FF; color: #1273EB; }
        .mp-metode-card .mp-check {
            width: 20px; height: 20px; border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s; flex-shrink: 0;
        }
        .mp-metode-card.selected .mp-check { background: #1273EB; border-color: #1273EB; }
        .mp-metode-card .mp-check svg         { display: none; }
        .mp-metode-card.selected .mp-check svg { display: block; }

        /* ── Item jasa di list ── */
        .mp-jasa-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: #F9FBFF; border: 1px solid #E5E9F2; border-radius: 10px;
        }
        .mp-jasa-num {
            min-width: 28px; height: 22px;
            background: #1273EB; color: #fff; border-radius: 5px;
            font-size: 11px; font-weight: 700;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .mp-jasa-nama  { flex: 1; font-size: 13px; font-weight: 600; color: #213F5C; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mp-jasa-harga { font-size: 13px; font-weight: 700; color: #16A34A; white-space: nowrap; }
        .mp-jasa-del {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px; background: #FFF5F5; border: 1px solid #FFE0E0;
            color: #FF4D4D; cursor: pointer; flex-shrink: 0; transition: background 0.12s;
        }
        .mp-jasa-del:hover { background: #FFEBEB; }

        /* ── Input focus ── */
        .mp-input:focus { border-color: #1273EB; box-shadow: 0 0 0 3px rgba(18,115,235,0.08); outline: none; }

        /* ── Error box ── */
        .mp-error-box {
            display: flex; align-items: flex-start; gap: 8px;
            padding: 10px 12px;
            background: #FFF5F5; border: 1.5px solid #FFD5D5; border-radius: 10px;
            font-size: 12px; color: #DC2626; font-weight: 500; line-height: 1.5;
        }

        /* ── Info box metode terpilih ── */
        .mp-info-box {
            padding: 10px 12px;
            background: #EAF2FF; border: 1.5px solid #B1D3FF; border-radius: 10px;
            font-size: 12px; color: #213F5C; font-weight: 500;
        }
        .mp-info-box span   { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #6B7280; font-weight: 600; margin-bottom: 2px; }
        .mp-info-box strong { font-size: 14px; font-weight: 700; color: #1273EB; }
    </style>

    {{-- ── MODAL ── --}}
    <div id="modalPembayaran" class="fixed inset-0 z-[998] items-center justify-center hidden"
        style="background: rgba(15,23,42,0.48); backdrop-filter: blur(3px);">

        <div class="mp-panel bg-white rounded-[24px] shadow-2xl w-full mx-4 overflow-hidden flex flex-col"
            style="max-width: 1060px; max-height: 92vh;">

            {{-- ── Header modal ── --}}
            <div class="px-7 pt-6 pb-5 border-b border-[#F0F4FA] flex-shrink-0 flex items-center justify-between">
                <div>
                    <h2 class="text-[19px] font-bold text-[#213F5C]">Proses Pembayaran Service</h2>
                    <p class="text-[12px] text-gray-400 mt-0.5">Isi biaya jasa, pilih metode, lalu cetak nota</p>
                </div>
                <button type="button" onclick="tutupModalPembayaran()"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition-colors flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- ── Body modal (scrollable) ── --}}
            <div class="flex-1 overflow-y-auto px-7 py-6">
                <div class="grid grid-cols-3 gap-5">

                    {{-- ═══ Kolom 1: Tambah Jasa Service ═══ --}}
                    <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-5 flex flex-col gap-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#1273EB] inline-block"></span>
                            <h3 class="text-[14px] font-bold text-[#1273EB]">Tambah Jasa Service</h3>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-[12px] font-bold text-[#213F5C] mb-1.5">
                                    Nama Jasa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="mpInputNama" placeholder="Contoh: Jasa Ganti Oli Mesin"
                                    class="mp-input w-full px-4 py-2.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl text-[13px] text-[#213F5C] placeholder-gray-300 transition-all">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-[#213F5C] mb-1.5">
                                    Biaya Jasa (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="mpInputBiaya" placeholder="Nominal biaya" min="0"
                                    class="mp-input w-full px-4 py-2.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl text-[13px] text-[#213F5C] placeholder-gray-300 transition-all">
                            </div>
                            <button type="button" id="mpBtnTambah"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-100 text-gray-400 rounded-xl font-bold text-[13px] transition-all cursor-not-allowed"
                                disabled onclick="mpTambahJasa()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Jasa
                            </button>
                        </div>

                        <div id="mpJasaSection" class="hidden space-y-3">
                            <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                                <p class="text-[12px] font-bold text-[#213F5C]">Daftar Jasa</p>
                                <span id="mpJasaBadge"
                                    class="px-2 py-0.5 bg-[#1273EB] text-white text-[11px] font-bold rounded-full">0</span>
                            </div>
                            <div id="mpJasaList" class="space-y-2 max-h-[220px] overflow-y-auto pr-0.5"></div>
                        </div>
                    </div>

                    {{-- ═══ Kolom 2: Metode Pembayaran ═══ --}}
                    <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-5 flex flex-col gap-3 shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="text-[14px] font-bold text-[#213F5C]">Metode Pembayaran</h3>
                        </div>
                        <p class="text-[11px] text-gray-400 -mt-1">Pilih salah satu metode</p>

                        <div class="space-y-2.5" id="mpMetodeList">
                            @foreach (['Tunai', 'BCA', 'Mandiri', 'BNI', 'BRI', 'QRIS'] as $m)
                                <div class="mp-metode-card" data-metode="{{ $m }}" onclick="mpPilihMetode('{{ $m }}')">
                                    <div class="flex items-center gap-3">
                                        @if ($m === 'Tunai')
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        @elseif($m === 'QRIS')
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        @endif
                                        <span>{{ $m }}</span>
                                    </div>
                                    <div class="mp-check">
                                        <svg class="w-3 h-3" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ═══ Kolom 3: Ringkasan & Aksi ═══ --}}
                    <div class="bg-white border border-[#E5E9F2] rounded-[18px] p-5 flex flex-col gap-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-[14px] font-bold text-[#213F5C]">Ringkasan Pembayaran</h3>
                        </div>

                        <div class="space-y-2 py-3 border-t border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] text-gray-500">Suku Cadang</span>
                                <span id="mpRingSC" class="text-[12px] font-bold text-[#213F5C]">Rp 0</span>
                            </div>
                            <div id="mpRingJasaRow" class="hidden flex justify-between items-center">
                                <span class="text-[12px] text-gray-500">Jasa (<span id="mpRingJasaCount">0</span> item)</span>
                                <span id="mpRingJasaAmt" class="text-[12px] font-bold text-[#213F5C]">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                <span class="text-[13px] font-bold text-[#213F5C]">Subtotal</span>
                                <span id="mpRingSubtotal" class="text-[14px] font-bold text-[#213F5C]">Rp 0</span>
                            </div>
                            <div id="mpRingDpRow" class="hidden flex justify-between items-center">
                                <span class="text-[12px] text-gray-500">Down Payment (sudah dibayar)</span>
                                <span id="mpRingDpAmt" class="text-[12px] font-bold text-[#F59E0B]">- Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-[#E5E9F2]" style="border-top-width:1.5px;">
                                <span class="text-[13px] font-bold text-[#213F5C]">Total yang Dibayar</span>
                                <span id="mpRingTotal" class="text-[16px] font-bold text-[#16A34A]">Rp 0</span>
                            </div>
                        </div>

                        <div id="mpRingMetodeBox" class="hidden mp-info-box">
                            <span>Metode Dipilih</span>
                            <strong id="mpRingMetodeLabel">-</strong>
                        </div>

                        <div id="mpErrJasa" class="mp-error-box">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Tambahkan minimal 1 jasa service</span>
                        </div>

                        <div id="mpErrMetode" class="mp-error-box" style="display:none">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Pilih metode pembayaran terlebih dahulu</span>
                        </div>

                        <div class="flex-1"></div>

                        <button type="button" id="mpBtnCetak"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all bg-gray-200 text-gray-400 cursor-not-allowed"
                            disabled onclick="mpHandleCetak()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H3.231a1.125 1.125 0 01-1.12-1.227L2.34 18m15.32 0H2.34" />
                            </svg>
                            Cetak Nota Pembayaran
                        </button>

                        <button type="button"
                            class="w-full flex items-center justify-center gap-2 py-3 bg-white border border-[#E5E9F2] text-[#213F5C] rounded-xl font-bold text-[14px] hover:bg-gray-50 transition-all"
                            onclick="mpHandleBatal()">
                            Batal & Tutup
                        </button>
                    </div>

                </div>{{-- end grid --}}
            </div>{{-- end body --}}
        </div>{{-- end mp-panel --}}
    </div>{{-- end modal --}}


    <script>
        // ════════════════════════════════════════════════════════════════════════════
        // CONFIG STATUS PENGERJAAN
        // ════════════════════════════════════════════════════════════════════════════
        const statusConfig = {
            'pengecekan' : { border: '#FDE68A', bg: '#FFF8EC', text: '#F59E0B', chevron: '#F59E0B', optClass: 'status-option-pengecekan'  },
            'menunggu'   : { border: '#E5E7EB', bg: '#F5F5F5', text: '#6B7280', chevron: '#6B7280', optClass: 'status-option-menunggu'     },
            'dikerjakan' : { border: '#B1D3FF', bg: '#EAF2FF', text: '#1273EB', chevron: '#1273EB', optClass: 'status-option-dalamproses'  },
            'dibatalkan' : { border: '#FFE0E0', bg: '#FFF5F5', text: '#FF4D4D', chevron: '#FF4D4D', optClass: 'status-option-dibatalkan'   },
            'selesai'    : { border: '#A7F3D0', bg: '#EDFBF3', text: '#16A34A', chevron: '#16A34A', optClass: 'status-option-selesai'      },
        };

        const paymentStatusConfig = {
            'belum_lunas' : { label: 'Belum Lunas', badgeClass: 'badge-payment-red'   },
            'down_payment': { label: 'DP',           badgeClass: 'badge-payment-amber' },
            'lunas'       : { label: 'Lunas',        badgeClass: 'badge-payment-green' },
        };

        const paymentApiMap = {
            'unpaid'     : 'belum_lunas',
            'dp'         : 'down_payment',
            'paid'       : 'lunas',
            'belum_lunas': 'belum_lunas',
            'down_payment':'down_payment',
            'lunas'      : 'lunas',
            'UNPAID'     : 'belum_lunas',
            'DP'         : 'down_payment',
            'PAID'       : 'lunas',
            '0'          : 'belum_lunas',
            '1'          : 'down_payment',
            '2'          : 'lunas',
            'pending'    : 'belum_lunas',
            'partial'    : 'down_payment',
            'full'       : 'lunas',
            'complete'   : 'lunas',
            'completed'  : 'lunas',
        };

        const statusList    = Object.keys(statusConfig);
        const btnPembayaran = document.getElementById('btnProsesPembayaran');
        const token         = localStorage.getItem('access_token');

        let currentStatus        = 'pengecekan';
        let currentPaymentStatus = 'belum_lunas';
        let isStatusDropOpen     = false;
        let currentTransactionId = null;

        // ════════════════════════════════════════════════════════════════════════════
        // DROPDOWN STATUS PENGERJAAN
        // ════════════════════════════════════════════════════════════════════════════

        function renderStatusOptions() {
            const container = document.getElementById('statusDropdownItems');
            container.innerHTML = '';
            statusList.forEach(status => {
                const cfg = statusConfig[status];
                const div = document.createElement('div');
                div.className = `status-option-item ${cfg.optClass}`;
                div.textContent = status;
                div.addEventListener('click', () => selectStatus(status));
                container.appendChild(div);
            });
        }

        async function selectStatus(newStatus) {
            if (newStatus === currentStatus) { closeStatusDropdown(); return; }

            const prevStatus = currentStatus;
            currentStatus    = newStatus;
            applyStatusStyle(newStatus);
            closeStatusDropdown();

            if (!currentTransactionId) return;

            try {
                const res = await fetch(`/api/transactions/${currentTransactionId}/status`, {
                    method : 'PUT',
                    headers: {
                        'Content-Type' : 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept'       : 'application/json',
                    },
                    body: JSON.stringify({ status_service: newStatus }),
                });
                const result = await res.json();

                if (res.ok) {
                    document.getElementById('updatedAt').textContent = formatTanggal(new Date().toISOString());

                    if (newStatus === 'selesai') {
                        // ── Redirect ke Riwayat Transaksi setelah status selesai ──
                        await Swal.fire({
                            icon            : 'success',
                            title           : 'Servis Selesai!',
                            text            : 'Status berhasil diubah ke "selesai". Mengalihkan ke Riwayat Transaksi...',
                            timer           : 2200,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                        window.location.href = "{{ route('riwayat-transaksi.index') }}";
                    } else {
                        Swal.fire({
                            icon : 'success',
                            title: 'Status diperbarui!',
                            text : `Status berhasil diubah ke "${newStatus}"`,
                            timer: 1800,
                            showConfirmButton: false,
                        });
                    }
                } else {
                    currentStatus = prevStatus;
                    applyStatusStyle(prevStatus);
                    Swal.fire('Gagal!', result.message ?? 'Status gagal diperbarui.', 'error');
                }
            } catch (err) {
                console.error(err);
                currentStatus = prevStatus;
                applyStatusStyle(prevStatus);
                Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
            }
        }

        function toggleStatusDropdown() { isStatusDropOpen ? closeStatusDropdown() : openStatusDropdown(); }

        function openStatusDropdown() {
            renderStatusOptions();
            document.getElementById('statusDropdownList').classList.remove('hidden');
            document.getElementById('statusDropdownList').style.display = 'block';
            document.getElementById('statusDropdownChevron').style.transform = 'rotate(180deg)';
            isStatusDropOpen = true;
        }

        function closeStatusDropdown() {
            document.getElementById('statusDropdownList').classList.add('hidden');
            document.getElementById('statusDropdownList').style.display = '';
            document.getElementById('statusDropdownChevron').style.transform = 'rotate(0deg)';
            isStatusDropOpen = false;
        }

        function applyStatusStyle(status) {
            const cfg     = statusConfig[status] || statusConfig['pengecekan'];
            const trigger = document.getElementById('statusDropdownTrigger');
            const label   = document.getElementById('statusDropdownLabel');
            const chevron = document.getElementById('statusDropdownChevron');
            trigger.style.borderColor      = cfg.border;
            trigger.style.backgroundColor  = cfg.bg;
            trigger.style.color            = cfg.text;
            chevron.style.color            = cfg.chevron;
            label.textContent              = status;
            updatePembayaranBtn(status);
        }

        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('statusDropdownWrapper');
            if (wrapper && !wrapper.contains(e.target)) closeStatusDropdown();
        });

        // ════════════════════════════════════════════════════════════════════════════
        // STATUS PEMBAYARAN — READ-ONLY BADGE
        // ════════════════════════════════════════════════════════════════════════════

        function applyPaymentStatusStyle(rawStatus) {
            const normalized = paymentApiMap[rawStatus]
                ?? paymentApiMap[String(rawStatus).toLowerCase()]
                ?? 'belum_lunas';

            const cfg   = paymentStatusConfig[normalized] ?? paymentStatusConfig['belum_lunas'];
            const badge = document.getElementById('paymentStatusBadge');
            if (!badge) return;

            badge.textContent = cfg.label;
            badge.className   = 'payment-status-badge ' + cfg.badgeClass;
            currentPaymentStatus = normalized;

            console.log(`[PaymentStatus] raw="${rawStatus}" → normalized="${normalized}" → label="${cfg.label}"`);
        }

        // ════════════════════════════════════════════════════════════════════════════
        // TOMBOL PROSES PEMBAYARAN
        // ════════════════════════════════════════════════════════════════════════════

        function updatePembayaranBtn(status) {
            if (status === 'selesai') {
                btnPembayaran.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                btnPembayaran.classList.add('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
                btnPembayaran.disabled = false;
            } else {
                btnPembayaran.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                btnPembayaran.classList.remove('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
                btnPembayaran.disabled = true;
            }
        }

        function handleProsesPembayaran() {
            const id = currentTransactionId ?? getAntrianId();
            if (!id) { Swal.fire('Error', 'ID transaksi tidak ditemukan!', 'error'); return; }
            mpReset();
            mpLoadSukuCadang(id);
            const modal = document.getElementById('modalPembayaran');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        // ════════════════════════════════════════════════════════════════════════════
        // HELPERS UMUM
        // ════════════════════════════════════════════════════════════════════════════

        function getAntrianId() {
            const fromSession = sessionStorage.getItem('currentAntrianId');
            if (fromSession) return parseInt(fromSession, 10);
            const segments = window.location.pathname.split('/').filter(Boolean);
            const lastSeg  = segments[segments.length - 1];
            const parsed   = parseInt(lastSeg, 10);
            return isNaN(parsed) ? null : parsed;
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        function formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            const tgl = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const jam = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            return `${tgl} pukul ${jam}`;
        }

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
        }

        // ════════════════════════════════════════════════════════════════════════════
        // RENDER DETAIL HALAMAN
        // ════════════════════════════════════════════════════════════════════════════

        function renderDetail(t) {
            console.log('[DEBUG] Full transaction data:', t);
            console.log('[DEBUG] status_payment  :', t.status_payment);
            console.log('[DEBUG] payment_status  :', t.payment_status);
            console.log('[DEBUG] status_service  :', t.status_service);

            const customer = t.vehicle?.customer ?? {};
            const vehicle  = t.vehicle ?? {};

            document.getElementById('detailName').textContent        = customer.name          || '-';
            document.getElementById('detailPhone').textContent       = customer.phone_number  || '-';
            document.getElementById('detailAddress').textContent     = customer.address       || '-';
            document.getElementById('detailCarModel').textContent    = vehicle.model          || '-';
            document.getElementById('detailKmMasuk').textContent     = t.km_masuk             || '-';
            document.getElementById('detailEngineCode').textContent  = vehicle.engine_code    || '-';
            document.getElementById('detailLicensePlate').textContent= vehicle.license_plate  || '-';

            const cabangMap = {
                'PELAJAR_PEJUANG': 'Pelajar Pejuang',
                'AHMAD_YANI'     : 'Ahmad Yani',
                '1'              : 'Pelajar Pejuang',
                '2'              : 'Ahmad Yani',
            };
            const branchRaw  = t.branch ?? t.cabang_id ?? null;
            const branchKey  = branchRaw ? String(branchRaw).toUpperCase().replace(/ /g, '_') : null;
            const cabangNama = branchKey ? (cabangMap[branchKey] ?? cabangMap[String(branchRaw)] ?? branchRaw) : null;
            document.getElementById('detailCabang').textContent = cabangNama ? '📍 ' + cabangNama : '-';

            const createdByName = t.creator?.name || 'Unknown';
            document.getElementById('createdByName').textContent    = createdByName;
            document.getElementById('createdByInitial').textContent = createdByName.charAt(0).toUpperCase();
            document.getElementById('createdAt').textContent        = formatTanggal(t.created_at);
            document.getElementById('updatedAt').textContent        = formatTanggal(t.updated_at);

            const editUrl = "{{ route('antrian-pengerjaan.edit', ':id') }}".replace(':id', t.transaction_id);
            const btnEdit = document.getElementById('btnEditData');
            if (btnEdit) {
                btnEdit.setAttribute('href', editUrl);
                btnEdit.addEventListener('click', (e) => {
                    e.preventDefault();
                    sessionStorage.setItem('currentAntrianId', t.transaction_id);
                    window.location.href = editUrl;
                });
            }

            currentStatus        = t.status_service || 'pengecekan';
            currentTransactionId = t.transaction_id;
            applyStatusStyle(currentStatus);

            const rawPayment = t.status_payment ?? t.payment_status ?? t.status_bayar ?? 'unpaid';
            applyPaymentStatusStyle(rawPayment);

            renderSukuCadang(t.items || []);
        }

        // ════════════════════════════════════════════════════════════════════════════
        // SUKU CADANG
        // ════════════════════════════════════════════════════════════════════════════

        function renderSukuCadang(items) {
            const container = document.getElementById('sukuCadangContainer');
            const emptyEl   = document.getElementById('sukuCadangEmpty');
            Array.from(container.querySelectorAll('.sc-item')).forEach(el => el.remove());
            if (!items || items.length === 0) { emptyEl.style.display = 'block'; return; }
            emptyEl.style.display = 'none';
            items.forEach(sc => {
                const nama      = sc.item_name ?? sc.nama ?? sc.sparepart?.name ?? '-';
                const deskripsi = sc.item_type ?? sc.deskripsi ?? sc.sparepart?.name ?? '-';
                const harga     = sc.price ?? sc.harga ?? (sc.sparepart ? 'Rp ' + Number(sc.sparepart.selling_price).toLocaleString('id-ID') : '-');
                const hargaFmt  = (typeof harga === 'number' || !isNaN(Number(harga))) ? 'Rp ' + Number(harga).toLocaleString('id-ID') : harga;
                const jumlah    = sc.qty ?? sc.quantity ?? sc.jumlah ?? 1;
                const tanggal   = sc.tanggal ?? sc.sparepart?.date ?? '-';
                const supplier  = sc.supplier ?? sc.sparepart?.supplier?.name ?? '-';
                const div = document.createElement('div');
                div.className = 'sc-item flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2]';
                div.innerHTML = `
                    <div>
                        <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(nama)}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${escHtml(deskripsi)}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(hargaFmt)}</p>
                            <p class="text-[11px] text-gray-400">${escHtml(String(jumlah))} pcs • ${escHtml(tanggal)}</p>
                            <p class="text-[11px] text-gray-400">Supplier: ${escHtml(supplier)}</p>
                        </div>
                    </div>`;
                container.appendChild(div);
            });
        }

        // ════════════════════════════════════════════════════════════════════════════
        // HAPUS TRANSAKSI
        // ════════════════════════════════════════════════════════════════════════════

        function handleHapus() {
            const id = currentTransactionId ?? getAntrianId();
            Swal.fire({
                title: 'Hapus Data?', text: 'Data antrian ini akan dihapus permanen.',
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#FF4D4D',
                cancelButtonText: 'Batal', confirmButtonText: 'Ya, Hapus!',
            }).then(async (result) => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                try {
                    const res = await fetch(`/api/transactions/${id}`, {
                        method : 'DELETE',
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                    });
                    if (res.ok) {
                        await Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
                        window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                    } else {
                        const r = await res.json();
                        Swal.fire('Gagal!', r.message ?? 'Gagal menghapus.', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
                }
            });
        }

        // ════════════════════════════════════════════════════════════════════════════
        // STATE MODAL PEMBAYARAN
        // ════════════════════════════════════════════════════════════════════════════
        let mpJasaList      = [];
        let mpSelectedMetode= null;
        let mpTotalSC       = 0;
        let mpDpAmount      = 0;
        let mpDpStatus      = null;

        function mpReset() {
            mpJasaList       = [];
            mpSelectedMetode = null;
            mpTotalSC        = 0;
            mpDpAmount       = 0;
            mpDpStatus       = null;

            document.getElementById('mpInputNama').value  = '';
            document.getElementById('mpInputBiaya').value = '';
            mpValidasiForm();
            mpRenderJasa();
            mpRenderRingkasan();
            document.querySelectorAll('.mp-metode-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('mpRingMetodeBox').classList.add('hidden');
        }

        async function mpLoadSukuCadang(id) {
            try {
                const res    = await fetch(`/api/transactions/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (!res.ok || result.status !== 'success') return;

                let totalSC = 0;
                (result.data.items || []).forEach(item => {
                    const harga  = item.harga_num ?? item.price ?? item.sparepart?.selling_price ?? 0;
                    const jumlah = item.quantity ?? item.qty ?? item.jumlah ?? 1;
                    totalSC += Number(harga) * Number(jumlah);
                });
                mpTotalSC  = totalSC;
                mpDpStatus = result.data.status_payment ?? 'unpaid';
                mpDpAmount = (mpDpStatus === 'dp' && result.data.dp_amount)
                    ? Number(result.data.dp_amount) : 0;

                mpRenderRingkasan();
            } catch (e) {
                console.error('mpLoadSukuCadang error:', e);
            }
        }

        function tutupModalPembayaran() {
            document.getElementById('modalPembayaran').classList.add('hidden');
            document.getElementById('modalPembayaran').classList.remove('flex');
            document.body.style.overflow = '';
        }

        function mpValidasiForm() {
            const nama  = document.getElementById('mpInputNama').value.trim();
            const biaya = document.getElementById('mpInputBiaya').value.trim();
            const btn   = document.getElementById('mpBtnTambah');
            if (nama && biaya && Number(biaya) >= 0) {
                btn.disabled  = false;
                btn.className = 'w-full flex items-center justify-center gap-2 py-2.5 bg-[#1273EB] text-white rounded-xl font-bold text-[13px] transition-all hover:bg-[#0E59B8] cursor-pointer';
            } else {
                btn.disabled  = true;
                btn.className = 'w-full flex items-center justify-center gap-2 py-2.5 bg-gray-100 text-gray-400 rounded-xl font-bold text-[13px] transition-all cursor-not-allowed';
            }
        }

        document.getElementById('mpInputNama').addEventListener('input', mpValidasiForm);
        document.getElementById('mpInputBiaya').addEventListener('input', mpValidasiForm);

        document.getElementById('mpInputNama').addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('mpInputBiaya').focus(); }
        });
        document.getElementById('mpInputBiaya').addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); const b = document.getElementById('mpBtnTambah'); if (!b.disabled) mpTambahJasa(); }
        });

        function mpTambahJasa() {
            const nama  = document.getElementById('mpInputNama').value.trim();
            const biaya = parseInt(document.getElementById('mpInputBiaya').value.trim()) || 0;
            if (!nama) { Swal.fire('Oops!', 'Nama jasa wajib diisi!', 'warning'); return; }
            mpJasaList.push({ id: Date.now(), nama, biaya });
            document.getElementById('mpInputNama').value  = '';
            document.getElementById('mpInputBiaya').value = '';
            mpValidasiForm();
            mpRenderJasa();
            mpRenderRingkasan();
        }

        function mpHapusJasa(id) {
            mpJasaList = mpJasaList.filter(j => j.id !== id);
            mpRenderJasa();
            mpRenderRingkasan();
        }

        function mpRenderJasa() {
            const list    = document.getElementById('mpJasaList');
            const section = document.getElementById('mpJasaSection');
            const badge   = document.getElementById('mpJasaBadge');
            list.innerHTML = '';
            if (mpJasaList.length === 0) { section.classList.add('hidden'); return; }
            section.classList.remove('hidden');
            badge.textContent = mpJasaList.length;
            mpJasaList.forEach((item, idx) => {
                const div = document.createElement('div');
                div.className = 'mp-jasa-item';
                div.innerHTML = `
                    <div class="mp-jasa-num">#${idx + 1}</div>
                    <span class="mp-jasa-nama" title="${escHtml(item.nama)}">${escHtml(item.nama)}</span>
                    <span class="mp-jasa-harga">${formatRupiah(item.biaya)}</span>
                    <button type="button" class="mp-jasa-del" onclick="mpHapusJasa(${item.id})" title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>`;
                list.appendChild(div);
            });
        }

        function mpPilihMetode(metode) {
            mpSelectedMetode = metode;
            document.querySelectorAll('.mp-metode-card').forEach(c => {
                c.classList.toggle('selected', c.dataset.metode === metode);
            });
            const box = document.getElementById('mpRingMetodeBox');
            box.classList.remove('hidden');
            document.getElementById('mpRingMetodeLabel').textContent = metode;
            mpRenderRingkasan();
        }

        function mpRenderRingkasan() {
            const hasJasa   = mpJasaList.length > 0;
            const hasMetode = !!mpSelectedMetode;
            const totalJasa = mpJasaList.reduce((acc, j) => acc + j.biaya, 0);
            const subtotal  = mpTotalSC + totalJasa;
            const totalAll  = Math.max(0, subtotal - mpDpAmount);

            document.getElementById('mpRingSC').textContent       = formatRupiah(mpTotalSC);
            document.getElementById('mpRingSubtotal').textContent = formatRupiah(subtotal);
            document.getElementById('mpRingTotal').textContent    = formatRupiah(totalAll);

            const jasaRow = document.getElementById('mpRingJasaRow');
            if (hasJasa) {
                jasaRow.classList.remove('hidden');
                document.getElementById('mpRingJasaCount').textContent = mpJasaList.length;
                document.getElementById('mpRingJasaAmt').textContent   = formatRupiah(totalJasa);
            } else {
                jasaRow.classList.add('hidden');
            }

            const dpRow = document.getElementById('mpRingDpRow');
            if (mpDpAmount > 0) {
                dpRow.classList.remove('hidden');
                document.getElementById('mpRingDpAmt').textContent = '- ' + formatRupiah(mpDpAmount);
            } else {
                dpRow.classList.add('hidden');
            }

            document.getElementById('mpErrJasa').style.display   = !hasJasa                ? 'flex' : 'none';
            document.getElementById('mpErrMetode').style.display = (hasJasa && !hasMetode) ? 'flex' : 'none';

            const btn = document.getElementById('mpBtnCetak');
            if (hasJasa && hasMetode) {
                btn.disabled  = false;
                btn.className = 'w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all bg-[#16A34A] text-white hover:bg-[#15803D] shadow-lg shadow-green-100 cursor-pointer';
            } else {
                btn.disabled  = true;
                btn.className = 'w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-[14px] transition-all bg-gray-200 text-gray-400 cursor-not-allowed';
            }
        }

        // ── Cetak nota → simpan flag → redirect ke preview nota ─────────────────
        function mpHandleCetak() {
            if (mpJasaList.length === 0) { Swal.fire('Oops!', 'Tambahkan minimal 1 jasa service!', 'warning'); return; }
            if (!mpSelectedMetode)       { Swal.fire('Oops!', 'Pilih metode pembayaran!', 'warning'); return; }

            const id        = currentTransactionId ?? getAntrianId();
            const totalJasa = mpJasaList.reduce((acc, j) => acc + j.biaya, 0);
            const subtotal  = mpTotalSC + totalJasa;
            const totalAll  = Math.max(0, subtotal - mpDpAmount);

            sessionStorage.setItem('notaPembayaran', JSON.stringify({
                transactionId   : id,
                jasaList        : mpJasaList,
                metode          : mpSelectedMetode,
                totalSukuCadang : mpTotalSC,
                totalJasa,
                subtotal,
                dpAmount        : mpDpAmount,
                dpStatus        : mpDpStatus,
                totalAll,
                tanggal         : new Date().toISOString(),
            }));

            // ── Flag: nota sudah dicetak, setelah kembali dari preview → riwayat ──
            sessionStorage.setItem('notaSudahDicetak', '1');

            window.location.href = `/antrian-pengerjaan/${id}/nota-preview`;
        }

        function mpHandleBatal() {
            if (mpJasaList.length > 0) {
                Swal.fire({
                    title: 'Batalkan Pembayaran?', text: 'Data jasa yang sudah diisi akan hilang.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#FF4D4D', cancelButtonText: 'Lanjut Isi', confirmButtonText: 'Ya, Batalkan',
                }).then(r => { if (r.isConfirmed) tutupModalPembayaran(); });
            } else {
                tutupModalPembayaran();
            }
        }

        document.getElementById('modalPembayaran').addEventListener('click', function (e) {
            if (e.target === this) mpHandleBatal();
        });

        // ════════════════════════════════════════════════════════════════════════════
        // INISIALISASI HALAMAN
        // ════════════════════════════════════════════════════════════════════════════

        document.addEventListener('DOMContentLoaded', async () => {
            const id = getAntrianId();

            // ── Jika baru kembali dari halaman preview nota → redirect riwayat ──
            if (sessionStorage.getItem('notaSudahDicetak') === '1') {
                sessionStorage.removeItem('notaSudahDicetak');
                window.location.href = "{{ route('riwayat-transaksi.index') }}";
                return;
            }

            if (id === null) {
                Swal.fire('Error', 'ID antrian tidak ditemukan!', 'error').then(() => {
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                });
                return;
            }

            try {
                const res    = await fetch(`/api/transactions/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await res.json();
                if (!res.ok || result.status !== 'success') {
                    Swal.fire('Error', 'Data antrian tidak ditemukan!', 'error').then(() => {
                        window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                    });
                    return;
                }
                renderDetail(result.data);
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Tidak bisa terhubung ke server.', 'error');
            }

            const name = localStorage.getItem('user_name') || 'User';
            const role = localStorage.getItem('user_role') || 'Staff';
            document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
            document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role);
            document.querySelectorAll('.user-initial-box').forEach(el => {
                if (!el.id || el.id !== 'createdByInitial') el.innerText = name.charAt(0).toUpperCase();
            });
        });
    </script>
@endsection