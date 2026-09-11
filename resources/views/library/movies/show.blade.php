<x-public-layout :title="$movie->title">
    <div
        class="relative"
        x-data="{
            files: {{ \Illuminate\Support\Js::from($playlist) }},
            currentId: {{ $playlist->first()['id'] ?? 'null' }},
            failed: false,
            get current() {
                return this.files.find((file) => file.id === this.currentId) ?? this.files[0] ?? null;
            },
            select(id, autoplay = false) {
                this.currentId = id;
                this.failed = false;
                this.$nextTick(() => {
                    if (this.$refs.player) {
                        this.$refs.player.load();
                        if (autoplay) {
                            this.$refs.player.play();
                        }
                    }
                });
            },
            playNext() {
                const index = this.files.findIndex((file) => file.id === this.currentId);
                const next = this.files[index + 1];
                if (next) {
                    this.select(next.id, true);
                }
            }
        }"
    >
        @if ($movie->hasPoster())
            <div class="pointer-events-none absolute inset-x-0 top-0 hidden h-[36rem] overflow-hidden sm:block">
                <img src="{{ route('library.movie.poster', $movie) }}" alt="" class="h-full w-full scale-110 object-cover opacity-25 blur-2xl">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0b0b0f]/20 via-[#0b0b0f]/70 to-[#0b0b0f]"></div>
            </div>
        @endif

        <div class="relative mx-auto max-w-6xl px-4 pb-24 pt-3 sm:px-8 sm:pt-4">
            <a href="{{ route('library.show', \App\Enums\MediaType::Movie) }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">← Фильмы</a>

            <div class="mt-4 flex items-start gap-4 sm:mt-8 sm:gap-6 lg:grid lg:grid-cols-[220px_minmax(0,1fr)] lg:gap-8">
                <div class="w-24 shrink-0 overflow-hidden rounded-xl ring-1 ring-white/15 sm:w-36 sm:rounded-2xl lg:w-full">
                    <div class="aspect-[2/3]">
                        <x-movie-cover :movie="$movie" />
                    </div>
                </div>

                <div class="min-w-0">
                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-4xl lg:text-6xl">{{ $movie->title }}</h1>

                    @if ($movie->original_title)
                        <p class="mt-2 text-sm text-white/50 sm:mt-3 sm:text-lg">{{ $movie->original_title }}</p>
                    @endif

                    <div class="mt-3 flex flex-wrap items-center gap-1.5 text-xs text-white/55 sm:mt-5 sm:gap-2 sm:text-sm">
                        @if ($movie->year)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $movie->year }}</span>
                        @endif
                        @foreach ($movie->genres as $genre)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $genre->name }}</span>
                        @endforeach
                        @foreach ($movie->countries as $country)
                            <span class="rounded-full bg-white/5 px-2.5 py-1 text-white/45 sm:px-3">{{ $country->name }}</span>
                        @endforeach
                    </div>

                    @if ($movie->description)
                        <p class="mt-4 hidden max-w-3xl text-base leading-relaxed text-white/70 sm:mt-6 sm:block">{{ $movie->description }}</p>
                    @endif
                </div>
            </div>

            <div class="-mx-4 mt-6 overflow-hidden bg-black sm:mx-0 sm:mt-12 sm:rounded-3xl sm:ring-1 sm:ring-white/10">
                @if ($playlist->isNotEmpty())
                    <video
                        x-ref="player"
                        class="aspect-video w-full bg-black"
                        controls
                        playsinline
                        preload="metadata"
                        :src="current?.src"
                        x-on:error="failed = true"
                        x-on:ended="playNext()"
                    ></video>
                    <p x-show="failed || (current && !current.playable)" class="px-4 py-3 text-sm text-amber-200/80 sm:px-5 sm:py-4">
                        Браузер может не проиграть этот формат
                        (<span x-text="current?.extension"></span>).
                        Лучше mp4 или webm.
                    </p>
                @else
                    <div class="flex aspect-video items-center justify-center px-6 text-center text-white/40">
                        К этому фильму ещё не добавлены файлы.
                    </div>
                @endif
            </div>

            @if ($movie->description)
                <p class="mt-6 text-sm leading-relaxed text-white/70 sm:hidden">{{ $movie->description }}</p>
            @endif

            @if ($playlist->isNotEmpty())
                <div class="mt-8 sm:mt-10">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40 sm:text-sm">Части</h2>
                    <div class="mt-3 grid gap-2 sm:mt-4 sm:gap-3 md:grid-cols-2">
                        @foreach ($playlist as $item)
                            <button
                                type="button"
                                class="min-h-14 rounded-2xl border p-3 text-left transition sm:p-4"
                                :class="currentId === {{ $item['id'] }} ? 'border-white/40 bg-white/10' : 'border-white/10 bg-white/5 hover:border-white/20 hover:bg-white/10'"
                                @click="select({{ $item['id'] }})"
                            >
                                <div class="font-medium text-white">{{ $item['title'] }}</div>
                                <div class="mt-1 text-xs text-white/40">
                                    @if ($item['year']){{ $item['year'] }} · @endif{{ strtoupper((string) $item['extension']) }}
                                </div>
                                @if ($item['description'])
                                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-white/55">{{ $item['description'] }}</p>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
