<x-public-layout :title="$type->label()">
    <div class="mx-auto max-w-7xl px-5 pb-20 pt-6 sm:px-8">
        <div class="mb-10 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/40">Библиотека</p>
                <h1 class="mt-2 text-4xl font-extrabold tracking-tight sm:text-5xl">{{ $type->label() }}</h1>
            </div>
            <a href="{{ route('home') }}" class="text-sm text-white/40 transition hover:text-white">На главную</a>
        </div>

        @if ($movies->isEmpty())
            <div class="rounded-3xl border border-white/10 bg-white/5 px-6 py-20 text-center">
                <p class="text-lg text-white/50">Пока пусто</p>
                <p class="mt-2 text-sm text-white/30">Когда добавишь фильмы в кабинете, они появятся здесь.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($movies as $movie)
                    <a href="{{ route('library.movie', $movie) }}" class="group block">
                        <div class="relative aspect-[2/3] overflow-hidden rounded-2xl bg-white/5 ring-1 ring-white/10 transition duration-300 group-hover:-translate-y-1 group-hover:ring-white/30 group-hover:shadow-[0_20px_50px_-20px_rgba(0,0,0,0.8)]">
                            <x-movie-cover :movie="$movie" class="transition duration-500 group-hover:scale-105" />
                            <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/70 to-transparent p-3 pt-16">
                                <div class="text-sm font-semibold leading-snug text-white sm:text-base">{{ $movie->title }}</div>
                                <div class="mt-1 text-xs text-white/55">
                                    @if ($movie->year){{ $movie->year }}@endif
                                    @if ($movie->files->isNotEmpty())
                                        @if ($movie->year) · @endif{{ $movie->files->count() }} {{ $movie->files->count() === 1 ? 'часть' : 'частей' }}
                                    @endif
                                </div>
                                @if ($movie->genres->isNotEmpty())
                                    <div class="mt-2 line-clamp-1 text-[11px] text-white/40">{{ $movie->genres->pluck('name')->join(' · ') }}</div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-public-layout>
