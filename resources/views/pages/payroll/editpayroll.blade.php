@extends('layouts.master')

@section('title', 'Edit Gaji Karyawan')
@section('title_header', 'Payroll | Edit Gaji Karyawan')

@section('form_icon')
    <div
        class="w-12 h-12 bg-[#1273EB] rounded-[15px] flex items-center justify-center text-white shadow-lg shadow-blue-200">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
        </svg>
    </div>
@endsection

@section('form_title', 'Edit Data Gaji Karyawan')

@section('form_fields')
    {{-- Loading skeleton --}}
    <div id="loadingState" class="space-y-6">
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4 animate-pulse">
            <div class="h-4 bg-gray-200 rounded w-1/3"></div>
            <div class="h-12 bg-gray-100 rounded-xl"></div>
            <div class="grid grid-cols-2 gap-4">
                <div class="h-12 bg-gray-100 rounded-xl"></div>
                <div class="h-12 bg-gray-100 rounded-xl"></div>
            </div>
            <div class="h-12 bg-gray-100 rounded-xl"></div>
        </div>
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-3 animate-pulse">
            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
            <div class="h-12 bg-gray-100 rounded-xl"></div>
        </div>
    </div>

    <form id="editPayrollForm" class="space-y-6 hidden">

        {{-- Informasi Pribadi Karyawan --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Informasi Pribadi Karyawan</h3>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Karyawan <span
                        class="text-red-500">*</span></label>
                <select id="employee_id" required
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                    <option value="" disabled selected>Pilih Karyawan</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Bulan <span
                            class="text-red-500">*</span></label>
                    <select id="month" required
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C]">
                        <option value="" disabled>Pilih Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Tahun <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="year" required inputmode="numeric" pattern="[0-9]*"
                        placeholder="Contoh: 2025"
                        class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
                </div>
            </div>

            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Gaji Pokok <span
                        class="text-red-500">*</span></label>
                <input type="text" id="basic_salary" required inputmode="numeric" pattern="[0-9]*"
                    placeholder="Contoh: 1200000"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none text-[14px] text-[#213F5C] placeholder-gray-400">
            </div>
        </div>

        {{-- Pemasukan --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Pemasukan</h3>
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Pendapatan Lain</label>
                <div id="income_list" class="space-y-3"></div>
                <button type="button" onclick="tambahPendapatan()"
                    class="mt-3 w-full py-3 bg-[#1273EB] text-white rounded-xl text-[14px] font-semibold hover:bg-blue-700 transition">
                    + Tambah Pendapatan Lain
                </button>
            </div>
        </div>

        {{-- Tabungan --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Tabungan</h3>
            </div>
            <div>
                <div id="saving_list" class="space-y-3"></div>
                <button type="button" onclick="tambahTabungan()"
                    class="mt-3 w-full py-3 bg-[#1273EB] text-white rounded-xl text-[14px] font-semibold hover:bg-blue-700 transition">
                    + Tambah Tabungan Lain
                </button>
            </div>
        </div>

        {{-- Penalti --}}
        <div class="bg-white border border-[#E5E9F2] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-[15px] font-bold text-[#213F5C]">Penalti</h3>
            </div>
            <div>
                <div id="penalty_list" class="space-y-3"></div>
                <button type="button" onclick="tambahPenalti()"
                    class="mt-3 w-full py-3 bg-[#1273EB] text-white rounded-xl text-[14px] font-semibold hover:bg-blue-700 transition">
                    + Tambah Penalti Lain
                </button>
            </div>
        </div>

    </form>
@endsection

@section('content')
    @include('layouts.form_wrapper', [
        'backUrl' => route('payroll.index'),
        'submitBtnText' => 'Simpan Perubahan',
    ])

    <script>
        // ─── ID dari URL ──────────────────────────────────────────────────────────
        const PAYROLL_ID = @json(request()->route('id'));

        // ─── Dynamic Row Helpers ──────────────────────────────────────────────────

        const INCOME_FIELDS = [
            { key: 'name',   label: 'Nama Pendapatan', placeholder: 'Contoh: Tunjangan Jabatan', required: true },
            { key: 'type',   label: 'Tipe', type: 'select', placeholder: 'Pilih Tipe', required: true,
              options: [{ value: 'Insentif', label: 'Insentif' }, { value: 'Direct Income', label: 'Direct Income' }, { value: 'Bonus', label: 'Bonus' }] },
            { key: 'amount', label: 'Nominal (Rp)', placeholder: 'Contoh: 1000000', required: true, inputmode: 'numeric' },
        ];

        const SAVING_FIELDS = [
            { key: 'name',         label: 'Nama Tabungan', placeholder: 'Contoh: Allowance Kesetiaan', required: true },
            { key: 'type',         label: 'Tipe', type: 'select', placeholder: 'Pilih Tipe', required: true,
              options: [{ value: 'Allowance', label: 'Allowance' }, { value: 'BPJS', label: 'BPJS' }, { value: 'Lainnya', label: 'Lainnya' }] },
            { key: 'amount',       label: 'Nominal (Rp)', placeholder: 'Contoh: 10000000', required: true, inputmode: 'numeric' },
            { key: 'month_target', label: 'Target Bulan Ke-', placeholder: 'Contoh: 40', inputmode: 'numeric' },
        ];

        const PENALTY_FIELDS = [
            { key: 'name',   label: 'Nama Penalti', placeholder: 'Contoh: Keterlambatan Pegawai', required: true },
            { key: 'info',   label: 'Keterangan', placeholder: 'Contoh: Total 15 Hari' },
            { key: 'amount', label: 'Nominal (Rp)', placeholder: 'Contoh: 200000', required: true, inputmode: 'numeric' },
        ];

        /**
         * Membuat row dinamis.
         * @param {string} containerId
         * @param {Array}  fields
         * @param {Object} [prefill={}]  — nilai default untuk setiap data-key
         */
        function buatRow(containerId, fields, prefill = {}) {
            const container = document.getElementById(containerId);
            const wrapper   = document.createElement('div');
            wrapper.className = 'relative bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl p-4 space-y-3';

            let inner = '';
            fields.forEach(f => {
                const val = prefill[f.key] ?? '';
                if (f.type === 'select') {
                    const opts = (f.options || []).map(o =>
                        `<option value="${o.value}" ${String(val) === String(o.value) ? 'selected' : ''}>${o.label}</option>`
                    ).join('');
                    inner += `
                        <div>
                            <label class="block text-[13px] font-semibold text-[#213F5C] mb-1">
                                ${f.label}${f.required ? ' <span class="text-red-500">*</span>' : ''}
                            </label>
                            <select data-key="${f.key}" ${f.required ? 'required' : ''}
                                class="w-full px-4 py-2.5 bg-white border border-[#E5E9F2] rounded-lg outline-none text-[13px] text-[#213F5C]">
                                <option value="" disabled ${!val ? 'selected' : ''}>${f.placeholder}</option>
                                ${opts}
                            </select>
                        </div>`;
                } else {
                    inner += `
                        <div>
                            <label class="block text-[13px] font-semibold text-[#213F5C] mb-1">
                                ${f.label}${f.required ? ' <span class="text-red-500">*</span>' : ''}
                            </label>
                            <input type="text" data-key="${f.key}" ${f.required ? 'required' : ''}
                                ${f.inputmode ? `inputmode="${f.inputmode}" pattern="[0-9]*"` : ''}
                                value="${val}"
                                placeholder="${f.placeholder}"
                                class="w-full px-4 py-2.5 bg-white border border-[#E5E9F2] rounded-lg outline-none text-[13px] text-[#213F5C] placeholder-gray-400">
                        </div>`;
                }
            });

            wrapper.innerHTML = `
                <button type="button" onclick="this.closest('.relative').remove()"
                    class="absolute top-3 right-3 text-red-400 hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                ${inner}
            `;

            container.appendChild(wrapper);
        }

        // Shortcut manual-add (tanpa prefill)
        const tambahPendapatan = () => buatRow('income_list',  INCOME_FIELDS);
        const tambahTabungan   = () => buatRow('saving_list',  SAVING_FIELDS);
        const tambahPenalti    = () => buatRow('penalty_list', PENALTY_FIELDS);

        // ─── Numeric Only ─────────────────────────────────────────────────────────

        document.addEventListener('keydown', (e) => {
            if (e.target.matches('input[inputmode="numeric"]')) {
                const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
                if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) e.preventDefault();
            }
        });

        document.addEventListener('paste', (e) => {
            if (e.target.matches('input[inputmode="numeric"]')) {
                if (!/^[0-9]+$/.test(e.clipboardData.getData('text'))) e.preventDefault();
            }
        });

        // ─── Collect Dynamic Rows ─────────────────────────────────────────────────

        function collectRows(containerId) {
            const rows = [];
            document.getElementById(containerId).querySelectorAll(':scope > .relative').forEach(wrapper => {
                const obj = {};
                wrapper.querySelectorAll('[data-key]').forEach(el => { obj[el.dataset.key] = el.value || null; });
                rows.push(obj);
            });
            return rows;
        }

        // ─── Prefill Form dari API ────────────────────────────────────────────────

        async function loadPayrollData() {
            const token = localStorage.getItem('access_token');
            try {
                // Muat karyawan + data payroll secara paralel
                const [empRes, payrollRes] = await Promise.all([
                    fetch('/api/employees', {
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    }),
                    fetch(`/api/payroll/${PAYROLL_ID}`, {
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    }),
                ]);

                const empResult     = await empRes.json();
                const payrollResult = await payrollRes.json();

                if (!payrollRes.ok) throw new Error(payrollResult.message ?? 'Data tidak ditemukan');

                const payroll   = payrollResult.data ?? payrollResult;
                const employees = empResult.data ?? empResult;

                // Isi dropdown karyawan
                const empSelect = document.getElementById('employee_id');
                employees.forEach(emp => {
                    const opt = document.createElement('option');
                    opt.value       = emp.id;
                    opt.textContent = `${emp.name} — ${emp.employee_number ?? emp.npk ?? ''}`;
                    if (String(emp.id) === String(payroll.employee_id)) opt.selected = true;
                    empSelect.appendChild(opt);
                });

                // Isi field statis
                document.getElementById('month').value        = payroll.month        ?? '';
                document.getElementById('year').value         = payroll.year         ?? '';
                document.getElementById('basic_salary').value = payroll.basic_salary ?? '';

                // Isi rows dinamis dari data yang ada
                (payroll.incomes   ?? []).forEach(r => buatRow('income_list',  INCOME_FIELDS,  { name: r.name, type: r.type, amount: r.amount }));
                (payroll.savings   ?? []).forEach(r => buatRow('saving_list',  SAVING_FIELDS,  { name: r.name, type: r.type, amount: r.amount, month_target: r.month_target }));
                (payroll.penalties ?? []).forEach(r => buatRow('penalty_list', PENALTY_FIELDS, { name: r.name, info: r.info, amount: r.amount }));

                // Tampilkan form, sembunyikan skeleton
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('editPayrollForm').classList.remove('hidden');

            } catch (err) {
                console.error(err);
                document.getElementById('loadingState').innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center text-red-500 text-[14px]">
                        <p class="font-bold mb-1">Gagal memuat data</p>
                        <p>${err.message}</p>
                    </div>`;
            }
        }

        loadPayrollData();

        // ─── Submit (PUT) ─────────────────────────────────────────────────────────

        document.getElementById('submitBtnApi').onclick = async (e) => {
            e.preventDefault();
            const token = localStorage.getItem('access_token');

            const data = {
                employee_id:  document.getElementById('employee_id').value  || null,
                month:        Number(document.getElementById('month').value) || null,
                year:         Number(document.getElementById('year').value)  || null,
                basic_salary: Number(document.getElementById('basic_salary').value) || 0,
                incomes: collectRows('income_list').map(r => ({
                    name:   r.name,
                    type:   r.type,
                    amount: Number(r.amount) || 0,
                })),
                savings: collectRows('saving_list').map(r => ({
                    name:         r.name,
                    type:         r.type,
                    amount:       Number(r.amount) || 0,
                    month_target: Number(r.month_target) || null,
                })),
                penalties: collectRows('penalty_list').map(r => ({
                    name:   r.name,
                    info:   r.info,
                    amount: Number(r.amount) || 0,
                })),
            };

            try {
                Swal.fire({
                    title: 'Menyimpan perubahan...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const response = await fetch(`/api/payroll/${PAYROLL_ID}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data gaji karyawan berhasil diperbarui.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    window.location.href = "{{ route('payroll.index') }}";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Update',
                        text: result.message || 'Cek kembali inputan kamu!'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke server.', 'error');
            }
        };
    </script>
@endsection