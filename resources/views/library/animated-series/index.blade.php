<x-public-layout :title="$type->label()">
    <div class="mx-auto max-w-7xl px-4 pb-24 pt-4 sm:px-8 sm:pt-6">
        <div class="mb-6 flex flex-col gap-2 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-white/40 sm:text-xs">Библиотека</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight sm:mt-2 sm:text-5xl">{{ $type->label() }}</h1>
            </div>
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">На главную</a>
        </div>

        @if ($seriesList->isEmpty())
            <div class="rounded-2xl border border-white/10 bg-white/5 px-5 py-16 text-center sm:rounded-3xl sm:px-6 sm:py-20">
                <p class="text-lg text-white/50">Пока пусто</p>
                <p class="mt-2 text-sm text-white/30">Когда добавишь мультсериалы в кабинете, они появятся здесь.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-5 md:grid-cols-4 xl:grid-cols-5">
                @foreach ($seriesList as $animatedSeries)
                    @php
                        $episodeCount = $animatedSeries->seasons->sum(fn ($season) => $season->episodes->count());
                    @endphp
                    <a href="{{ route('library.animated-series', $animatedSeries) }}" class="group block min-w-0">
                        <div class="relative aspect-[2/3] overflow-hidden rounded-xl bg-white/5 ring-1 ring-white/10 transition duration-300 active:scale-[0.98] sm:rounded-2xl sm:group-hover:-translate-y-1 sm:group-hover:ring-white/30 sm:group-hover:shadow-[0_20px_50px_-20px_rgba(0,0,0,0.8)]">
                            <x-animated-series-cover :animated-series="$animatedSeries" class="transition duration-500 sm:group-hover:scale-105" />
                            <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/70 to-transparent p-2 pt-12 sm:p-3 sm:pt-16">
                                <div class="line-clamp-2 text-xs font-semibold leading-snug text-white sm:text-base">{{ $animatedSeries->title }}</div>
                                <div class="mt-1 text-[11px] text-white/55 sm:text-xs">
                                    @if ($animatedSeries->year){{ $animatedSeries->year }}@endif
                                    @if ($animatedSeries->seasons->isNotEmpty())
                                        @if ($animatedSeries->year) · @endif{{ $animatedSeries->seasons->count() }} {{ $animatedSeries->seasons->count() === 1 ? 'сезон' : 'сезонов' }}
                                        @if ($episodeCount)
                                            · {{ $episodeCount }} {{ $episodeCount === 1 ? 'серия' : 'серий' }}
                                        @endif
                                    @endif
                                </div>
                                @if ($animatedSeries->genres->isNotEmpty())
                                    <div class="mt-1 hidden line-clamp-1 text-[11px] text-white/40 sm:mt-2 sm:block">{{ $animatedSeries->genres->pluck('name')->join(' · ') }}</div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-public-layout>
