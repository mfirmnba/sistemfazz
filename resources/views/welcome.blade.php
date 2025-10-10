<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | FazzDrink</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-red-600 via-pink-500 to-orange-400 h-screen flex items-center justify-center font-sans">

    <div class="relative w-full h-full flex items-center justify-center">
        {{-- Efek bokeh background --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-yellow-300/20 rounded-full blur-3xl animate-ping"></div>
        </div>

        {{-- Kotak Login --}}
        <div class="relative z-10 bg-white/20 backdrop-blur-xl shadow-2xl rounded-3xl p-10 text-center border border-white/30">
            <h1 class="text-4xl md:text-6xl font-bold text-white drop-shadow-lg mb-4 tracking-wide">
                Login
            </h1>

            <a href="{{ route('login') }}"
               class="inline-block mt-4 px-8 py-3 bg-white/30 text-white font-semibold rounded-full shadow hover:bg-white/50 transition duration-300 backdrop-blur-sm">
               Masuk Sekarang →
            </a>
        </div>

        {{-- Footer kecil --}}
        <p class="absolute bottom-4 text-white/80 text-sm">
            © {{ date('Y') }} FazzDrink. Stay Fresh, Stay Cool ☕
        </p>
    </div>

</body>
</html>
