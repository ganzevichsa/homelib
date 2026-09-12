<x-public-layout :title="$album->title">
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
        @if ($album->hasPoster())
            <div class="pointer-events-none absolute inset-x-0 top-0 hidden h-[36rem] overflow-hidden sm:block">
                <img src="{{ route('library.music.poster', $album) }}" alt="" class="h-full w-full scale-110 object-cover opacity-25 blur-2xl">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0b0b0f]/20 via-[#0b0b0f]/70 to-[#0b0b0f]"></div>
            </div>
        @endif

        <div class="relative mx-auto max-w-6xl px-4 pb-24 pt-3 sm:px-8 sm:pt-4">
            <a href="{{ route('library.show', \App\Enums\MediaType::Music) }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">← Музыка</a>

            <div class="mt-4 flex items-start gap-4 sm:mt-8 sm:gap-6 lg:grid lg:grid-cols-[220px_minmax(0,1fr)] lg:gap-8">
                <div class="w-24 shrink-0 overflow-hidden rounded-xl ring-1 ring-white/15 sm:w-36 sm:rounded-2xl lg:w-full">
                    <div class="aspect-square">
                        <x-album-cover :album="$album" />
                    </div>
                </div>

                <div class="min-w-0">
                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-4xl lg:text-6xl">{{ $album->title }}</h1>
                    <p class="mt-2 text-sm text-white/70 sm:mt-3 sm:text-lg">{{ $album->artist }}</p>

                    @if ($album->original_title)
                        <p class="mt-1 text-sm text-white/40 sm:text-base">{{ $album->original_title }}</p>
                    @endif

                    <div class="mt-3 flex flex-wrap items-center gap-1.5 text-xs text-white/55 sm:mt-5 sm:gap-2 sm:text-sm">
                        @if ($album->year)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $album->year }}</span>
                        @endif
                        @foreach ($album->genres as $genre)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    @if ($album->description)
                        <p class="mt-4 hidden max-w-3xl text-base leading-relaxed text-white/70 sm:mt-6 sm:block">{{ $album->description }}</p>
                    @endif
                </div>
            </div>

            <div class="-mx-4 mt-6 overflow-hidden bg-black/40 sm:mx-0 sm:mt-12 sm:rounded-3xl sm:ring-1 sm:ring-white/10">
                @if ($playlist->isNotEmpty())
                    <div class="px-4 py-4 sm:px-5 sm:py-5">
                        <p class="mb-3 text-sm font-medium text-white" x-text="current?.title"></p>
                        <audio
                            x-ref="player"
                            class="w-full"
                            controls
                            preload="metadata"
                            :src="current?.src"
                            x-on:error="failed = true"
                            x-on:ended="playNext()"
                        ></audio>
                    </div>
                    <p x-show="failed || (current && !current.playable)" class="px-4 pb-4 text-sm text-amber-200/80 sm:px-5">
                        Браузер может не проиграть этот формат
                        (<span x-text="current?.extension"></span>).
                        Лучше mp3, m4a или ogg.
                    </p>
                @else
                    <div class="flex min-h-32 items-center justify-center px-6 py-10 text-center text-white/40">
                        К этому альбому ещё не добавлены треки.
                    </div>
                @endif
            </div>

            @if ($album->description)
                <p class="mt-6 text-sm leading-relaxed text-white/70 sm:hidden">{{ $album->description }}</p>
            @endif

            @if ($playlist->isNotEmpty())
                <div class="mt-8 sm:mt-10">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40 sm:text-sm">Треки</h2>
                    <div class="mt-3 space-y-2">
                        @foreach ($playlist as $item)
                            <button
                                type="button"
                                class="flex min-h-12 w-full items-center gap-3 rounded-2xl border px-3 py-2.5 text-left transition sm:px-4"
                                :class="currentId === {{ $item['id'] }} ? 'border-white/40 bg-white/10' : 'border-white/10 bg-white/5 hover:border-white/20 hover:bg-white/10'"
                                @click="select({{ $item['id'] }}, true)"
                            >
                                <span class="w-6 shrink-0 text-sm text-white/40">{{ $item['number'] }}</span>
                                <span class="min-w-0 flex-1 font-medium text-white">{{ $item['title'] }}</span>
                                <span class="shrink-0 text-xs uppercase text-white/35">{{ $item['extension'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
