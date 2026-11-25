<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — RentalGo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-slate-800 antialiased">
    <div class="flex min-h-full items-center justify-center px-4 py-10 sm:px-6">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-slate-900">RentalGo</a>
                <p class="mt-1 text-xs text-slate-500">Sistem Rental Kendaraan</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" class="mb-5" />
                @endif

                @if (session('error'))
                    <x-alert type="error" :message="session('error')" class="mb-5" />
                @endif

                @yield('content')
            </div>

            <p class="mt-6 text-center text-xs text-slate-500">
                © {{ date('Y') }} RentalGo
            </p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
