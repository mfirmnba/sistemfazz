<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Driver Dashboard</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* ====== RESPONSIVE DRIVER LAYOUT ====== */
            @media (max-width: 1024px) {
                aside {
                    position: fixed;
                    left: 0;
                    top: 0;
                    bottom: 0;
                    width: 16rem; /* w-64 */
                    background: #1f2937; /* bg-gray-800 */
                    z-index: 50;
                    transform: translateX(-100%);
                    transition: transform 0.3s ease-in-out;
                }

                aside.show {
                    transform: translateX(0);
                }

                main {
                    width: 100%;
                }

                /* Overlay saat sidebar dibuka */
                .overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 40;
                    display: none;
                }

                .overlay.active {
                    display: block;
                }
            }
        </style>
    </head>

    <body class="bg-gray-100 text-gray-800">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside
                id="sidebar"
                class="w-64 bg-gray-800 text-white flex flex-col"
            >
                <div
                    class="p-6 text-center font-bold text-2xl border-b border-black-600"
                >
                    Driver Panel
                </div>
                <nav class="flex-1 p-4 space-y-2">
                    <a
                        href="{{ route('driver.dashboard') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-950 {{ request()->routeIs('driver.dashboard') ?  : '' }}"
                    >
                        📊 Dashboard
                    </a>
                    <a
                        href="{{ route('driver.laporanpenjualan.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-950 {{ request()->routeIs('driver.laporanpenjualan.*') ?  : '' }}"
                    >
                        📝 Laporan Penjualan
                    </a>
                    <a
                        href="{{ route('driver.laporanpenjualan.create') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-950"
                    >
                        ➕ Tambah Laporan
                    </a>
                </nav>
                <div class="p-4 border-t border-black-600">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full text-left px-4 py-2 rounded bg-red-800 hover:bg-red-950"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Overlay (HP/tablet) -->
            <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Header -->
                <header
                    class="bg-white shadow p-4 flex justify-between items-center"
                >
                    <!-- Tombol toggle sidebar di HP -->
                    <button
                        class="lg:hidden bg-gray-800 text-white px-3 py-1 rounded"
                        onclick="toggleSidebar()"
                    >
                        ☰
                    </button>

                    <h1 class="text-xl font-semibold">
                        {{ $title ?? "Driver Dashboard" }}
                    </h1>
                    <div>
                        <span
                            class="font-bold"
                            >{{ auth()->user()->name }}</span
                        >
                        | Driver
                    </div>
                </header>

                <!-- Content -->
                <div class="p-6">@yield('content')</div>
            </main>
        </div>

        <script>
            // Toggle sidebar di layar kecil
            function toggleSidebar() {
                const sidebar = document.getElementById("sidebar");
                const overlay = document.getElementById("overlay");
                sidebar.classList.toggle("show");
                overlay.classList.toggle("active");
            }
        </script>
    </body>
</html>
