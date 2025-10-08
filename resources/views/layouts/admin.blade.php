<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <title>Admin Panel</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white h-screen p-6 fixed">
            <h2 class="text-2xl font-bold mb-8">Admin Panel</h2>
            <nav class="space-y-4">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="block py-2 px-3 rounded hover:bg-gray-900 transition"
                    >📊 Dashboard</a
                >
                <a
                    href="{{ route('admin.minuman.index') }}"
                    class="block py-2 px-3 rounded hover:bg-gray-900 transition"
                    >🥤 Minuman</a
                >
                <a
                    href="{{ route('admin.stock.index') }}"
                    class="block py-2 px-3 rounded hover:bg-gray-900 transition"
                    >📦 Stock</a
                >
                <div class="p-4 border-t border-gray-700 mt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full text-left px-4 py-2 rounded bg-red-800 hover:bg-red-600 transition"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Content -->
        <main class="ml-64 flex-1 p-10">@yield('content')</main>
    </body>
</html>
