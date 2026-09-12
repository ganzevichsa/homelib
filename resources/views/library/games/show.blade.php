<x-public-layout :title="$game->title">
    <div class="relative">
        @if ($game->hasPoster())
            <div class="pointer-events-none absolute inset-x-0 top-0 hidden h-[36rem] overflow-hidden sm:block">
                <img src="{{ route('library.game.poster', $game) }}" alt="" class="h-full w-full scale-110 object-cover opacity-25 blur-2xl">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0b0b0f]/20 via-[#0b0b0f]/70 to-[#0b0b0f]"></div>
            </div>
        @endif

        <div class="relative mx-auto max-w-6xl px-4 pb-24 pt-3 sm:px-8 sm:pt-4">
            <a href="{{ route('library.show', \App\Enums\MediaType::Game) }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">← Игры</a>

            <div class="mt-4 flex items-start gap-4 sm:mt-8 sm:gap-6 lg:grid lg:grid-cols-[220px_minmax(0,1fr)] lg:gap-8">
                <div class="w-24 shrink-0 overflow-hidden rounded-xl ring-1 ring-white/15 sm:w-36 sm:rounded-2xl lg:w-full">
                    <div class="aspect-[2/3]">
                        <x-game-cover :game="$game" />
                    </div>
                </div>

                <div class="min-w-0">
                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-4xl lg:text-6xl">{{ $game->title }}</h1>

                    <div class="mt-3 flex flex-wrap items-center gap-1.5 text-xs text-white/55 sm:mt-5 sm:gap-2 sm:text-sm">
                        @if ($game->year)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $game->year }}</span>
                        @endif
                    </div>

                    @if ($game->description)
                        <p class="mt-4 hidden max-w-3xl text-base leading-relaxed text-white/70 sm:mt-6 sm:block">{{ $game->description }}</p>
                    @endif
                </div>
            </div>

            @if ($game->description)
                <p class="mt-6 text-sm leading-relaxed text-white/70 sm:hidden">{{ $game->description }}</p>
            @endif

            <div class="mt-8 sm:mt-10">
                <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40 sm:text-sm">Файлы</h2>
                @if ($game->files->isEmpty())
                    <p class="mt-4 text-white/40">К этой игре ещё не добавлены файлы.</p>
                @else
                    <div class="mt-3 grid gap-2 sm:gap-3 md:grid-cols-2">
                        @foreach ($game->files as $file)
                            <a
                                href="{{ route('library.game.download', [$game, $file]) }}"
                                class="min-h-14 rounded-2xl border border-white/10 bg-white/5 p-3 transition hover:border-white/20 hover:bg-white/10 sm:p-4"
                            >
                                <div class="font-medium text-white">{{ $file->title }}</div>
                                <div class="mt-1 text-xs uppercase text-white/40">{{ $file->filename }}</div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-public-layout>
