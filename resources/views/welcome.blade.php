<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | FazzDrink</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-red-700 via-red-500 to-rose-500 h-screen flex items-center justify-center font-sans">

    <div class="relative w-full h-full flex items-center justify-center">
        {{-- Efek bokeh background --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-red-300/20 rounded-full blur-3xl animate-ping"></div>
        </div>

        {{-- Kotak Login --}}
        <div class="relative z-10 bg-white/10 backdrop-blur-2xl shadow-2xl rounded-3xl p-10 text-center border border-white/30 max-w-md w-[90%]">
            
            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo FazzDrink" class="w-24 h-24 rounded-full shadow-lg border-2 border-white/50 object-cover">
            </div>

            <h1 class="text-4xl md:text-5xl font-extrabold text-white drop-shadow-lg mb-6 tracking-wide">
                Login
            </h1>

            <a href="{{ route('login') }}"
               class="inline-block mt-2 px-10 py-3 bg-gradient-to-r from-red-600 to-rose-500 text-white font-semibold rounded-full shadow-lg hover:scale-105 transition-transform duration-300">
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
