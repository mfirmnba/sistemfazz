<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard - Owner</title>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.js'])


        <script src="//unpkg.com/alpinejs" defer></script>
    </head>
    <style>
        /* ======== RESPONSIVE FIXES TANPA UBAH STRUKTUR ======== */

        /* Default untuk desktop tetap */
        @media (min-width: 1025px) {
            aside {
                transform: translateX(0) !important;
                position: fixed;
            }
        }

        /* Untuk layar tablet dan HP */
        @media (max-width: 1024px) {
            /* Sidebar jadi drawer */
            aside {
                position: fixed !important;
                z-index: 50 !important;
                height: 100vh;
                transform: translateX(-100%);
            }

            /* Saat toggle aktif (handled by Alpine) */
            [x-data] aside[style*="translate-x-0"] {
                transform: translateX(0) !important;
            }

            /* Tambahkan ruang supaya konten tidak ketindih */
            .ml-64 {
                margin-left: 0 !important;
            }

            /* Navbar biar elemen tidak tumpuk */
            header {
                flex-wrap: wrap;
                gap: 0.75rem;
                text-align: center;
            }

            header h1 {
                width: 100%;
                font-size: 1rem !important;
                order: 3;
            }

            header .flex.items-center.gap-2 {
                order: 1;
            }

            header .flex.items-center.gap-3 {
                order: 2;
            }

            /* Gambar logo kecil di HP */
            header img,
            aside img {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }

            /* Judul Sidebar lebih kecil */
            aside span {
                font-size: 1rem !important;
            }

            /* Menu Sidebar lebih rapat */
            aside ul li a {
                padding: 0.6rem 1rem !important;
                font-size: 0.9rem !important;
            }

            /* Tombol logout kecil */
            button.bg-red-500 {
                padding: 0.3rem 0.8rem !important;
                font-size: 0.8rem !important;
            }

            /* Main padding lebih kecil */
            main {
                padding: 1rem !important;
            }
        }

        /* Untuk layar sangat kecil (HP < 400px) */
        @media (max-width: 400px) {
            header h1 {
                font-size: 0.9rem !important;
            }

            span.text-xl {
                font-size: 1rem !important;
            }

            button.bg-red-500 {
                padding: 0.25rem 0.6rem !important;
            }
        }
    </style>

    <body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">
        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            <aside
                class="fixed inset-y-0 left-0 w-64 bg-white shadow-md transform transition-transform duration-300 z-50"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                {{-- Logo + Judul di Sidebar --}}
                <div
                    class="flex items-center gap-2 p-6 text-xl font-bold text-red-700 cursor-pointer"
                    @click="sidebarOpen = false"
                >
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt="Logo"
                        class="w-10 h-10 rounded-full object-cover shadow"
                    />
                    <span>Fazz Drink</span>
                </div>

                <ul class="space-y-2">
                    <li>
                        <a
                            href="#"
                            class="flex items-center p-3 text-gray-700 hover:bg-blue-100 rounded"
                        >
                            📊 Dashboard</a
                        >
                    </li>
                    <li>
                        <a
                            href="#"
                            class="flex items-center p-3 text-gray-700 hover:bg-blue-100 rounded"
                        >
                            🤖 AI Chat</a
                        >
                    </li>
                    <li>
                        <a
                            href="#"
                            class="flex items-center p-3 text-gray-700 hover:bg-blue-100 rounded"
                        >
                            📦 Stock</a
                        >
                    </li>
                    <li>
                        <a
                            href="#"
                            class="flex items-center p-3 text-gray-700 hover:bg-blue-100 rounded"
                        >
                            ⚙ Settings</a
                        >
                    </li>
                </ul>
            </aside>

            {{-- Main Content --}}
            <div class="flex-1 ml-0">
                {{-- Navbar --}}
                <header
                    class="flex justify-between items-center bg-white p-4 shadow"
                >
                    {{-- Logo + Teks di Navbar --}}
                    <div
                        class="flex items-center gap-2 cursor-pointer"
                        @click="sidebarOpen = !sidebarOpen"
                    >
                        <img
                            src="{{ asset('images/logo.jpg') }}"
                            alt="Logo"
                            class="w-10 h-10 rounded-full object-cover shadow"
                        />
                        <span class="text-xl font-bold text-red-700"
                            >Fazz Drink</span
                        >
                    </div>

                    <h1 class="text-lg font-bold">Lo Lemot Kalo Lagi Haus!</h1>

                    <div class="flex items-center gap-3">
                        <span class="font-semibold">Owner</span>

                        {{-- Tombol Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700 transition"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </header>
                <main class="p-6">@yield('content')</main>
            </div>
        </div>
        @yield('scripts')
    </body>
</html>
