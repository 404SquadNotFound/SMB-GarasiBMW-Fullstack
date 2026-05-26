<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GarasiBMW - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/login-assets/login-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'bmw-dark': '#213F5C',
                        'bmw-light-bg': '#EFF7FA',
                        'bmw-active-btn': '#D3D9DE',
                        'bmw-content-bg': '#F8FAFC',
                        'bmw-blue': '#1273EB',
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-item svg {
            transition: transform 0.2s;
        }

        .sidebar-item[aria-expanded="true"] svg.arrow {
            transform: rotate(180deg);
        }

        body {
            font-size: 14px;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #D9E2EC;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #CBD5E0;
        }
    </style>
</head>

<body class="bg-bmw-content-bg font-sans text-[#102A43] antialiased">
    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <main class="flex-1 h-screen overflow-y-auto p-6">

            <div class="max-w-screen-2xl mx-auto w-full flex flex-col min-h-full">

                <header
                    class="bg-white rounded-xl border border-[#D9E2EC] p-4 flex items-center justify-between shadow-sm mb-5">
                    <h2 class="text-[20px] font-bold text-bmw-dark">@yield('title_header', 'Dashboard')</h2>

                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center gap-2.5 bg-[#F7F9FC] border border-[#D9E2EC] px-3 h-12 rounded-lg">
                            <div id="user-initial"
                                class="w-9 h-9 rounded-full bg-[#213F5C] flex items-center justify-center text-white font-bold text-[14px]">
                                ?
                            </div>
                            <div class="leading-none">
                                <p id="user-name-header" class="text-[13px] font-bold text-[#213F5C]">Loading...</p>
                                <p id="user-role-header"
                                    class="text-[11px] font-bold text-[#1273EB] mt-0.5 bg-[#E3EAFA] px-2 py-0.5 rounded-full inline-block uppercase tracking-wider">
                                    ...
                                </p>
                            </div>
                        </div>

                        <button onclick="handleLogout()"
                            class="flex items-center justify-center gap-2 bg-[#FFF5F5] border border-[#FFDADA] text-[#CF3C3C] font-bold px-5 h-12 rounded-lg text-[13px] hover:bg-[#FFE8E8] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Keluar
                        </button>
                    </div>
                </header>

                <div class="flex-1 flex flex-col">
                    @yield('content')
                </div>

                <footer class="mt-8 pb-4 text-[13px] text-[#627D98]">
                    © 2026, Sistem Manajemen Bengkel GARASIBMW | 404SquadNotFound
                </footer>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const name = localStorage.getItem('user_name') || 'User';
            const role = localStorage.getItem('user_role') || 'Guest';

            // Update Nama & Role
            document.getElementById('user-name-header').innerText = name;
            document.getElementById('user-role-header').innerText = role.replace(/_/g, ' ');

            // Update Inisial (Ambil huruf pertama)
            document.getElementById('user-initial').innerText = name.charAt(0).toUpperCase();
        });

        // ── Fungsi global: update header nama & role tanpa logout ──
        function refreshUserHeader(name, role) {
            // Simpan ke localStorage supaya konsisten di semua halaman
            localStorage.setItem('user_name', name);
            localStorage.setItem('user_role', role);

            // Update DOM header langsung
            const nameEl = document.getElementById('user-name-header');
            const roleEl = document.getElementById('user-role-header');
            const initialEl = document.getElementById('user-initial');

            if (nameEl) nameEl.innerText = name;
            if (roleEl) roleEl.innerText = role.replace(/_/g, ' ');
            if (initialEl) initialEl.innerText = name.charAt(0).toUpperCase();
        }

        async function handleLogout() {
            const token = localStorage.getItem('access_token');
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
            } finally {
                localStorage.clear();
                window.location.href = '/';
            }
        }

        /**
         * Global pagination helper.
         * @param {object} result  - API paginated response (with current_page, last_page, from, to, total)
         * @param {function} fetchFn - The fetch function to call when a page button is clicked, receives (page)
         */
        function renderPaginationControls(result, fetchFn) {
            const container = document.getElementById('paginationControls');
            const fromEl    = document.getElementById('paginationFrom');
            const toEl      = document.getElementById('paginationTo');
            const totalEl   = document.getElementById('paginationTotal');

            if (fromEl)  fromEl.innerText  = result.from  ?? 0;
            if (toEl)    toEl.innerText    = result.to    ?? 0;
            if (totalEl) totalEl.innerText = result.total ?? 0;

            if (!container) return;
            container.innerHTML = '';

            const currentPage = result.current_page ?? 1;
            const lastPage    = result.last_page    ?? 1;

            if (lastPage <= 1) return;

            // Style helpers
            const btnBase    = 'w-8 h-8 flex items-center justify-center rounded-lg text-[12px] font-bold transition-all';
            const btnActive  = 'bg-[#1273EB] text-white shadow-sm';
            const btnNormal  = 'bg-white border border-[#D9E2EC] text-[#627D98] hover:bg-[#EAF2FF] hover:text-[#1273EB]';
            const btnDisabled= 'bg-[#F0F4F8] text-[#CBD5E1] cursor-not-allowed border border-[#E2E8F0]';

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '‹';
            prevBtn.className = `${btnBase} ${currentPage === 1 ? btnDisabled : btnNormal}`;
            prevBtn.disabled  = currentPage === 1;
            if (currentPage > 1) prevBtn.addEventListener('click', () => fetchFn(currentPage - 1));
            container.appendChild(prevBtn);

            // Page number buttons (show max 5 around current)
            let startPage = Math.max(1, currentPage - 2);
            let endPage   = Math.min(lastPage, currentPage + 2);
            if (endPage - startPage < 4) {
                if (startPage === 1) endPage = Math.min(lastPage, startPage + 4);
                else startPage = Math.max(1, endPage - 4);
            }

            if (startPage > 1) {
                const first = document.createElement('button');
                first.textContent = '1';
                first.className = `${btnBase} ${btnNormal}`;
                first.addEventListener('click', () => fetchFn(1));
                container.appendChild(first);
                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.textContent = '…';
                    dots.className = 'w-6 text-center text-[#627D98] text-[12px]';
                    container.appendChild(dots);
                }
            }

            for (let p = startPage; p <= endPage; p++) {
                const btn = document.createElement('button');
                btn.textContent = p;
                btn.className = `${btnBase} ${p === currentPage ? btnActive : btnNormal}`;
                if (p !== currentPage) btn.addEventListener('click', () => fetchFn(p));
                container.appendChild(btn);
            }

            if (endPage < lastPage) {
                if (endPage < lastPage - 1) {
                    const dots = document.createElement('span');
                    dots.textContent = '…';
                    dots.className = 'w-6 text-center text-[#627D98] text-[12px]';
                    container.appendChild(dots);
                }
                const last = document.createElement('button');
                last.textContent = lastPage;
                last.className = `${btnBase} ${btnNormal}`;
                last.addEventListener('click', () => fetchFn(lastPage));
                container.appendChild(last);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '›';
            nextBtn.className = `${btnBase} ${currentPage === lastPage ? btnDisabled : btnNormal}`;
            nextBtn.disabled  = currentPage === lastPage;
            if (currentPage < lastPage) nextBtn.addEventListener('click', () => fetchFn(currentPage + 1));
            container.appendChild(nextBtn);
        }
    </script>
</body>

<!-- <body class="bg-primary-light font-sans text-[#102A43] antialiased">
    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <main class="flex-1 p-6 overflow-y-auto h-screen">

            <header
                class="bg-white rounded-xl border border-[#D9E2EC] p-4 flex items-center justify-between shadow-sm mb-5">
                <h2 class="text-[20px] font-bold text-bmw-dark">@yield('title_header', 'Dashboard')</h2>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2.5 bg-[#F7F9FC] border border-[#D9E2EC] px-3 py-1.5 rounded-lg">
                        <div
                            class="w-9 h-9 rounded-full bg-[#213F5C] flex items-center justify-center text-white font-bold text-[14px]">
                            E</div>
                        <div class="leading-none">
                            <p class="text-[13px] font-bold text-[#213F5C]">Edsel Septa Haryanto</p>
                            <p
                                class="text-[11px] font-bold text-[#1273EB] mt-0.5 bg-[#E3EAFA] px-2 py-0.5 rounded-full inline-block uppercase tracking-wider">
                                Developer</p>
                        </div>
                    </div>
                    <button
                        class="flex items-center gap-2 bg-[#FFF5F5] border border-[#FFDADA] text-[#CF3C3C] font-bold px-5 py-2.5 rounded-lg text-[13px] hover:bg-[#FFE8E8] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Keluar
                    </button>
                </div>
            </header>
            @yield('content')

            <footer class="mt-6 text-[13px] text-[#627D98]">
                © 2026, Sistem Manajemen Bengkel GARASIBMW | 404SquadNotFound
            </footer>
        </main>
    </div>
</body> -->

</html>