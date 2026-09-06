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
            select(id) {
                this.currentId = id;
                this.failed = false;
                this.$nextTick(() => {
                    if (this.$refs.player) {
                        this.$refs.player.load();
                    }
                });
            }
        }"
    >
        @if ($movie->hasPoster())
            <div class="pointer-events-none absolute inset-x-0 top-0 h-[36rem] overflow-hidden">
                <img src="{{ route('library.movie.poster', $movie) }}" alt="" class="h-full w-full scale-110 object-cover opacity-25 blur-2xl">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0b0b0f]/20 via-[#0b0b0f]/70 to-[#0b0b0f]"></div>
            </div>
        @endif

        <div class="relative mx-auto max-w-6xl px-5 pb-20 pt-4 sm:px-8">
            <a href="{{ route('library.show', \App\Enums\MediaType::Movie) }}" class="text-sm text-white/40 transition hover:text-white">← Фильмы</a>

            <div class="mt-8 grid items-start gap-8 lg:grid-cols-[220px_minmax(0,1fr)]">
                <div class="mx-auto aspect-[2/3] w-44 overflow-hidden rounded-2xl ring-1 ring-white/15 sm:w-52 lg:w-full">
                    <x-movie-cover :movie="$movie" />
                </div>

                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-6xl">{{ $movie->title }}</h1>

                    @if ($movie->original_title)
                        <p class="mt-3 text-lg text-white/50">{{ $movie->original_title }}</p>
                    @endif

                    <div class="mt-5 flex flex-wrap items-center gap-2 text-sm text-white/55">
                        @if ($movie->year)
                            <span class="rounded-full bg-white/10 px-3 py-1">{{ $movie->year }}</span>
                        @endif
                        @foreach ($movie->genres as $genre)
                            <span class="rounded-full bg-white/10 px-3 py-1">{{ $genre->name }}</span>
                        @endforeach
                        @foreach ($movie->countries as $country)
                            <span class="rounded-full bg-white/5 px-3 py-1 text-white/45">{{ $country->name }}</span>
                        @endforeach
                    </div>

                    @if ($movie->description)
                        <p class="mt-6 max-w-3xl text-base leading-relaxed text-white/70">{{ $movie->description }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-12 overflow-hidden rounded-3xl bg-black ring-1 ring-white/10">
                @auth
                    @if ($playlist->isNotEmpty())
                        <video
                            x-ref="player"
                            class="aspect-video w-full bg-black"
                            controls
                            playsinline
                            preload="metadata"
                            :src="current?.src"
                            x-on:error="failed = true"
                        ></video>
                        <p x-show="failed || (current && !current.playable)" class="px-5 py-4 text-sm text-amber-200/80">
                            Браузер может не проиграть этот формат
                            (<span x-text="current?.extension"></span>).
                            Лучше mp4 или webm.
                        </p>
                    @else
                        <div class="flex aspect-video items-center justify-center px-6 text-center text-white/40">
                            К этому фильму ещё не добавлены файлы.
                        </div>
                    @endif
                @else
                    <div class="flex aspect-video flex-col items-center justify-center gap-4 px-6 text-center">
                        <p class="text-lg text-white/70">Чтобы смотреть, войди в библиотеку.</p>
                        <a href="{{ route('login') }}" class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-black transition hover:bg-white/90">
                            Войти
                        </a>
                    </div>
                @endauth
            </div>

            @if ($playlist->isNotEmpty())
                <div class="mt-10">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-white/40">Части</h2>
                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        @foreach ($playlist as $item)
                            <button
                                type="button"
                                class="rounded-2xl border p-4 text-left transition"
                                :class="currentId === {{ $item['id'] }} ? 'border-white/40 bg-white/10' : 'border-white/10 bg-white/5 hover:border-white/20 hover:bg-white/10'"
                                @click="select({{ $item['id'] }})"
                            >
                                <div class="font-medium text-white">{{ $item['title'] }}</div>
                                <div class="mt-1 text-xs text-white/40">
                                    @if ($item['year']){{ $item['year'] }} · @endif{{ strtoupper((string) $item['extension']) }}
                                </div>
                                @if ($item['description'])
                                    <p class="mt-2 text-sm leading-relaxed text-white/55">{{ $item['description'] }}</p>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
