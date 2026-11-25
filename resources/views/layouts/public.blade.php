<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog Kendaraan') — RentalGo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col font-sans text-slate-800 antialiased">
    @php
        $customer = auth('customer')->user();
    @endphp

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-14 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-base font-bold text-slate-900">RentalGo</a>

                <nav class="hidden items-center gap-4 text-sm md:flex" aria-label="Navigasi portal">
                    <a
                        href="{{ route('home') }}"
                        class="{{ request()->routeIs('home', 'portal.*') ? 'font-semibold text-indigo-600' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        Katalog
                    </a>

                    @if ($customer)
                        <a
                            href="{{ route('customer.rentals.index') }}"
                            class="{{ request()->routeIs('customer.rentals.*') ? 'font-semibold text-indigo-600' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Pesanan Saya
                        </a>
                        <a
                            href="{{ route('customer.profile.edit') }}"
                            class="{{ request()->routeIs('customer.profile.*') ? 'font-semibold text-indigo-600' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Profil
                        </a>
                    @endif
                </nav>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                @if ($customer)
                    <span class="text-sm text-slate-600">{{ $customer->name }}</span>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <x-button type="submit" variant="secondary" size="sm">Keluar</x-button>
                    </form>
                @else
                    <a href="{{ route('customer.login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Masuk</a>
                    <x-button :href="route('customer.register')" variant="primary" size="sm">Daftar</x-button>
                @endif
            </div>

            <button
                type="button"
                data-public-menu-toggle
                aria-label="Buka menu navigasi"
                aria-expanded="false"
                aria-controls="public-mobile-menu"
                class="rounded-md p-1.5 text-slate-600 hover:bg-slate-100 md:hidden"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <div id="public-mobile-menu" data-public-menu class="hidden border-t border-slate-200 bg-white px-4 py-3 md:hidden">
            <nav class="space-y-1 text-sm" aria-label="Navigasi seluler">
                <a href="{{ route('home') }}" class="block rounded-md px-2 py-1.5 text-slate-700 hover:bg-slate-100">Katalog</a>

                @if ($customer)
                    <a href="{{ route('customer.rentals.index') }}" class="block rounded-md px-2 py-1.5 text-slate-700 hover:bg-slate-100">Pesanan Saya</a>
                    <a href="{{ route('customer.profile.edit') }}" class="block rounded-md px-2 py-1.5 text-slate-700 hover:bg-slate-100">Profil</a>

                    <form method="POST" action="{{ route('customer.logout') }}" class="pt-2">
                        @csrf
                        <x-button type="submit" variant="secondary" size="sm" class="w-full">Keluar ({{ $customer->name }})</x-button>
                    </form>
                @else
                    <div class="flex gap-2 pt-2">
                        <x-button :href="route('customer.login')" variant="secondary" size="sm" class="flex-1">Masuk</x-button>
                        <x-button :href="route('customer.register')" variant="primary" size="sm" class="flex-1">Daftar</x-button>
                    </div>
                @endif
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-6 sm:px-6">
        @if (session('success'))
            <x-alert type="success" :message="session('success')" class="mb-5" />
        @endif

        @if (session('error'))
            <x-alert type="error" :message="session('error')" class="mb-5" />
        @endif

        @if ($errors->any())
            <x-alert type="error" message="Periksa kembali data yang Anda masukkan." class="mb-5" />
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 text-xs text-slate-500 sm:px-6">
            <p>© {{ date('Y') }} RentalGo</p>
            <p>Tarif dalam IDR</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
