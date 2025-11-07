<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | FazzDrink</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-rose-100 via-red-200 to-rose-300 h-screen flex items-center justify-center font-sans">

    <div class="relative w-full h-full flex items-center justify-center">
        {{-- Efek bokeh background --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-rose-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-red-300/20 rounded-full blur-3xl animate-ping"></div>
        </div>

        {{-- Kotak Login --}}
        <div class="relative z-10 bg-white/40 backdrop-blur-xl shadow-xl rounded-3xl p-10 text-center border border-white/40 max-w-md w-[90%] transition-transform hover:scale-[1.01] duration-300">
            
            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo FazzDrink" class="w-24 h-24 rounded-full shadow-md border-2 border-white/60 object-cover">
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-rose-700 drop-shadow-sm mb-6 tracking-wide">
                Login
            </h1>

            <a href="{{ route('login') }}"
               class="inline-block mt-2 px-10 py-3 bg-gradient-to-r from-rose-500 to-red-400 text-white font-semibold rounded-full shadow-md hover:from-rose-600 hover:to-red-500 hover:shadow-lg transition-all duration-300">
               Masuk Sekarang →
            </a>
        </div>

        {{-- Footer kecil --}}
        <p class="absolute bottom-4 text-rose-700/80 text-sm">
            © {{ date('Y') }} FazzDrink. Stay Fresh, Stay Cool ☕
        </p>
    </div>

</body>
</html>
