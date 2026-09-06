@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#0b0b0f] text-white antialiased" style="font-family: Manrope, ui-sans-serif, system-ui, sans-serif">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-32 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-violet-600/20 blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-cyan-500/10 blur-[110px]"></div>
        </div>

        <header class="relative z-10 flex items-center justify-between px-6 py-5 sm:px-10">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-[0.2em] uppercase text-white/70 hover:text-white">
                Homelib
            </a>

            <nav class="flex items-center gap-4 text-sm text-white/60">
                <a href="{{ route('library.show', \App\Enums\MediaType::Movie) }}" class="hover:text-white">Фильмы</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-white">Кабинет</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-white">Войти</a>
                @endauth
            </nav>
        </header>

        <main class="relative z-10">
            {{ $slot }}
        </main>
    </body>
</html>
