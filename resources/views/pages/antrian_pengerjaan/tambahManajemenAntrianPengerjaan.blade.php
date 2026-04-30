{{-- resources/views/pages/antrian_pengerjaan/tambahManajemenAntrianPengerjaan.blade.php --}}
@extends('layouts.master')

@section('title', 'Tambah Antrian Pengerjaan')
@section('title_header', 'Layanan Servis | Antrian Pengerjaan')

@section('form_icon')
    <div class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
        </svg>
    </div>
@endsection

@section('form_title', 'Menambahkan Mobil Masuk')

@section('form_fields')
    {{-- =========================================================
         SECTION 1 : Informasi Pemilik Kendaraan
    ========================================================= --}}
    <div class="space-y-5">
        <div class="flex items-center gap-2 pb-3 border-b border-[#F0F4FA]">
            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h3 class="text-[14px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h3>
        </div>

        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" id="name" required placeholder="Masukkan nama lengkap"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Nomor Telepon <span class="text-red-500">*</span>
            </label>
            <input type="text" id="phone" required placeholder="Masukkan nomor telepon"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">
                Alamat <span class="text-red-500">*</span>
            </label>
            <input type="text" id="address" required placeholder="Masukkan alamat lengkap"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
    </div>

    {{-- =========================================================
         SECTION 2 : Informasi Mobil Pelanggan
    ========================================================= --}}
    <div class="space-y-5 pt-2">
        <div class="flex items-center gap-2 pb-3 border-b border-[#F0F4FA]">
            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16H6l-2-6h15l-1 4M3 11l1-4h14" />
            </svg>
            <h3 class="text-[14px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h3>
        </div>

        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Mobil</label>
            <input type="text" id="car_model" placeholder="Masukkan model mobil"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nomor Polisi</label>
            <input type="text" id="license_plate" placeholder="Masukkan nomor polisi"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Kode Mesin</label>
            <input type="text" id="engine_code" placeholder="Masukkan kode mesin"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <div>
            <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Km Masuk Mobil</label>
            <input type="text" id="km_masuk" placeholder="Masukkan kilometer"
                class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
        </div>
        <!-- <div> -->
            <!-- <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Cabang Bengkel</label>
            {{-- TODO Backend: isi <option> dari $cabangList yang dikirim Controller --}}
            <div class="relative">
                <select id="cabang_id"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-gray-400 appearance-none">
                    <option value="" disabled selected>Pilih Cabang</option>
                    <option value="1">Cabang Utama - Bandung</option>
                    <option value="2">Cabang Jakarta Selatan</option>
                    {{-- TODO Backend: @foreach($cabangList as $c) <option value="{{ $c->id }}">{{ $c->nama }}</option> @endforeach --}}
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div> -->
        <!-- </div> -->
    </div>

    {{-- =========================================================
         SECTION 3 : Penggunaan Suku Cadang
    ========================================================= --}}
    <div class="space-y-4 pt-2">
        <div class="flex items-center gap-2 pb-3 border-b border-[#F0F4FA]">
            <svg class="w-4 h-4 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h3 class="text-[14px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h3>
        </div>

        {{-- List item suku cadang yang sudah ditambahkan --}}
        <div id="sukuCadangList" class="space-y-3"></div>

        {{-- Form inline tambah suku cadang (hidden by default) --}}
        <div id="formSukuCadang" class="hidden border border-[#E5E9F2] rounded-[14px] p-5 bg-[#F9FBFF] space-y-4">
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Barang</label>
                <input type="text" id="inputNamaBarang" placeholder="Contoh: Filter Oli BMW"
                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Stok</label>
                <input type="text" id="inputStok" placeholder="Pilih Stok Yang Ingin Digunakan"
                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
                {{-- TODO Backend: ganti dengan <select> berisi $sukuCadangList --}}
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Jumlah Stok Yang Digunakan</label>
                <input type="number" id="inputJumlah" placeholder="Contoh: 1" min="1"
                    class="w-full px-5 py-3.5 bg-white border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" id="btnSimpanSukuCadang"
                    class="flex-1 py-3 bg-[#1273EB] text-white rounded-xl font-bold text-[13px] hover:bg-[#0E59B8] transition-all">
                    Simpan
                </button>
                <button type="button" id="btnBatalSukuCadang"
                    class="px-6 py-3 bg-white border border-[#E5E9F2] text-[#213F5C] rounded-xl font-bold text-[13px] hover:bg-gray-50 transition-all">
                    Batal
                </button>
            </div>
        </div>

        {{-- Tombol Tambah Suku Cadang --}}
        <button type="button" id="btnTambahSukuCadang"
            class="w-full flex items-center justify-center gap-2 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[14px] hover:bg-[#0E59B8] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Suku Cadang
        </button>

        {{-- Hidden input — kirim data suku cadang sebagai JSON ke backend --}}
        {{-- TODO Backend: json_decode($request->suku_cadang) di Controller --}}
        <input type="hidden" id="inputSukuCadangJSON" name="suku_cadang" value="[]">
    </div>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl'       => route('antrian-pengerjaan.index'),
        'submitBtnText' => 'Simpan Data',
        'sectionTitle'  => 'Informasi Data Servis',
    ])

    <script>
        let isDirty = false;

        // ── Suku Cadang State ─────────────────────────────────────────────────
        let sukuCadangItems = [];
        // TODO Backend (edit mode): let sukuCadangItems = @json($sukuCadangExisting ?? []);

        const btnTambah   = document.getElementById('btnTambahSukuCadang');
        const formSC      = document.getElementById('formSukuCadang');
        const btnSimpanSC = document.getElementById('btnSimpanSukuCadang');
        const btnBatalSC  = document.getElementById('btnBatalSukuCadang');
        const listEl      = document.getElementById('sukuCadangList');
        const hiddenJSON  = document.getElementById('inputSukuCadangJSON');

        btnTambah.addEventListener('click', () => {
            formSC.classList.remove('hidden');
            btnTambah.classList.add('hidden');
            document.getElementById('inputNamaBarang').value = '';
            document.getElementById('inputStok').value = '';
            document.getElementById('inputJumlah').value = '';
        });

        btnBatalSC.addEventListener('click', () => {
            formSC.classList.add('hidden');
            btnTambah.classList.remove('hidden');
        });

        btnSimpanSC.addEventListener('click', () => {
            const nama   = document.getElementById('inputNamaBarang').value.trim();
            const stok   = document.getElementById('inputStok').value.trim();
            const jumlah = document.getElementById('inputJumlah').value.trim();

            if (!nama || !jumlah) {
                Swal.fire('Oops!', 'Nama barang dan jumlah wajib diisi!', 'warning');
                return;
            }

            sukuCadangItems.push({ id: Date.now(), nama, stok, jumlah });
            renderSukuCadang();
            syncHiddenJSON();
            formSC.classList.add('hidden');
            btnTambah.classList.remove('hidden');
        });

        function hapusSukuCadang(id) {
            sukuCadangItems = sukuCadangItems.filter(i => i.id !== id);
            renderSukuCadang();
            syncHiddenJSON();
        }

        function syncHiddenJSON() {
            hiddenJSON.value = JSON.stringify(sukuCadangItems);
        }

        function renderSukuCadang() {
            listEl.innerHTML = '';
            sukuCadangItems.forEach(item => {
                const el = document.createElement('div');
                el.className = 'flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[12px] border border-[#E5E9F2]';
                el.innerHTML = `
                    <div>
                        <p class="text-[13px] font-bold text-[#213F5C]">${item.nama}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${item.stok || '-'}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[12px] font-bold text-[#213F5C]">${item.jumlah} pcs</span>
                        <button type="button" onclick="hapusSukuCadang(${item.id})"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#FFF5F5] border border-[#FFE0E0] text-[#FF4D4D] hover:bg-[#FFEBEB] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                listEl.appendChild(el);
            });
        }

        // ── Dirty flag ────────────────────────────────────────────────────────
        document.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('input', () => isDirty = true);
        });

        window.addEventListener('beforeunload', (e) => {
            if (isDirty) { e.preventDefault(); e.returnValue = ''; }
        });

        // ── Submit via API (sama polanya seperti jenis-mesin) ─────────────────
        document.getElementById('submitBtnApi').addEventListener('click', async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('access_token');

            const data = {
                name          : document.getElementById('name').value,
                phone         : document.getElementById('phone').value,
                address       : document.getElementById('address').value,
                car_model     : document.getElementById('car_model').value,
                license_plate : document.getElementById('license_plate').value,
                engine_code   : document.getElementById('engine_code').value,
                km_masuk      : document.getElementById('km_masuk').value,
                cabang_id     : document.getElementById('cabang_id').value,
                suku_cadang   : sukuCadangItems,
            };

            if (!data.name || !data.phone || !data.address) {
                Swal.fire('Oops!', 'Nama, nomor telepon, dan alamat wajib diisi!', 'warning');
                return;
            }

            try {
                Swal.fire({ title: 'Menyimpan data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                // TODO Backend: sesuaikan endpoint API-nya
                const response = await fetch('/api/antrian-pengerjaan', {
                    method: 'POST',
                    headers: {
                        'Accept'       : 'application/json',
                        'Content-Type' : 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    isDirty = false;
                    await Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 2000, showConfirmButton: false });
                    window.location.href = "{{ route('antrian-pengerjaan.index') }}";
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Cek lagi inputan kamu!' });
                }
            } catch (error) {
                Swal.fire('Error', 'Koneksi server bermasalah.', 'error');
            }
        });

        // ── Enter key trigger submit ──────────────────────────────────────────
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('submitBtnApi')?.click();
            }
        });
    </script>
@endsection