@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#0b0b0f] text-white antialiased" style="font-family: Manrope, ui-sans-serif, system-ui, sans-serif">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-violet-600/20 blur-[120px] sm:h-80 sm:w-80"></div>
            <div class="absolute bottom-0 right-0 h-52 w-52 rounded-full bg-cyan-500/10 blur-[110px] sm:h-72 sm:w-72"></div>
        </div>

        <header class="sticky top-0 z-20 flex items-center justify-between gap-3 bg-[#0b0b0f]/80 px-4 py-3 backdrop-blur-md sm:px-8 sm:py-5" style="padding-top: max(0.75rem, env(safe-area-inset-top))">
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-xs font-semibold tracking-[0.2em] uppercase text-white/70 hover:text-white sm:text-sm">
                Homelib
            </a>

            <nav class="flex items-center gap-1 text-sm text-white/60 sm:gap-3">
                <a href="{{ route('library.show', \App\Enums\MediaType::Movie) }}" class="inline-flex min-h-11 items-center px-2 hover:text-white">Фильмы</a>
                <a href="{{ route('library.show', \App\Enums\MediaType::Series) }}" class="inline-flex min-h-11 items-center px-2 hover:text-white">Сериалы</a>
                <a href="{{ route('library.show', \App\Enums\MediaType::Cartoon) }}" class="hidden min-h-11 items-center px-2 hover:text-white sm:inline-flex">Мультфильмы</a>
                <a href="{{ route('library.show', \App\Enums\MediaType::AnimatedSeries) }}" class="hidden min-h-11 items-center px-2 hover:text-white sm:inline-flex">Мультсериалы</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex min-h-11 items-center px-2 hover:text-white">Кабинет</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center px-2 hover:text-white">Войти</a>
                @endauth
            </nav>
        </header>

        <main class="relative z-10">
            {{ $slot }}
        </main>
    </body>
</html>
