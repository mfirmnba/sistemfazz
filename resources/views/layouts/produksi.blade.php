<!-- resources/views/layouts/produksi.blade.php -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title', 'Dashboard Produksi')</title>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link
            href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css"
            rel="stylesheet"
        />
        <style>
            /* ====== RESPONSIVE PRODUKSI LAYOUT ====== */
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

    <body class="bg-gray-100 flex min-h-screen">
        <!-- Sidebar -->
        <aside
            id="sidebar"
            class="w-64 bg-gray-800 text-white min-h-screen flex flex-col"
        >
            <div class="p-6 font-bold text-xl border-b border-gray-700">
                Produksi
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a
                    href="{{ route('produksi.dashboard') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('produksi.dashboard') ? 'bg-gray-700' : '' }}"
                >
                    Dashboard
                </a>
                <a
                    href="{{ route('produksi.laporanproduksi.index') }}"
                    class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('produksi.laporanproduksi.*') ? 'bg-gray-700' : '' }}"
                >
                    Laporan Produksi
                </a>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <a
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="block px-4 py-2 rounded hover:bg-gray-700"
                >
                    Logout
                </a>
                <form
                    id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    class="hidden"
                >
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Overlay (untuk HP) -->
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
                    {{ $title ?? "Produksi Dashboard" }}
                </h1>
                <div>
                    <span class="font-bold">{{ auth()->user()->name }}</span> |
                    Produksi
                </div>
            </header>

            <!-- Content -->
            <div class="p-6">@yield('content')</div>
        </main>

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
