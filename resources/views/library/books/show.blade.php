<x-public-layout :title="$book->title">
    <div
        class="relative"
        x-data="{
            files: {{ \Illuminate\Support\Js::from($playlist) }},
            currentId: {{ $playlist->first()['id'] ?? 'null' }},
            get current() {
                return this.files.find((file) => file.id === this.currentId) ?? this.files[0] ?? null;
            },
            select(id) {
                this.currentId = id;
            }
        }"
    >
        @if ($book->hasPoster())
            <div class="pointer-events-none absolute inset-x-0 top-0 hidden h-[36rem] overflow-hidden sm:block">
                <img src="{{ route('library.book.poster', $book) }}" alt="" class="h-full w-full scale-110 object-cover opacity-25 blur-2xl">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0b0b0f]/20 via-[#0b0b0f]/70 to-[#0b0b0f]"></div>
            </div>
        @endif

        <div class="relative mx-auto max-w-6xl px-4 pb-24 pt-3 sm:px-8 sm:pt-4">
            <a href="{{ route('library.show', \App\Enums\MediaType::Book) }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">← Книги</a>

            <div class="mt-4 flex items-start gap-4 sm:mt-8 sm:gap-6 lg:grid lg:grid-cols-[220px_minmax(0,1fr)] lg:gap-8">
                <div class="w-24 shrink-0 overflow-hidden rounded-xl ring-1 ring-white/15 sm:w-36 sm:rounded-2xl lg:w-full">
                    <div class="aspect-[2/3]">
                        <x-book-cover :book="$book" />
                    </div>
                </div>

                <div class="min-w-0">
                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-4xl lg:text-6xl">{{ $book->title }}</h1>
                    <p class="mt-2 text-sm text-white/70 sm:mt-3 sm:text-lg">{{ $book->author }}</p>

                    @if ($book->original_title)
                        <p class="mt-1 text-sm text-white/40 sm:text-base">{{ $book->original_title }}</p>
                    @endif

                    <div class="mt-3 flex flex-wrap items-center gap-1.5 text-xs text-white/55 sm:mt-5 sm:gap-2 sm:text-sm">
                        @if ($book->year)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $book->year }}</span>
                        @endif
                        @if ($book->isbn)
                            <span class="rounded-full bg-white/5 px-2.5 py-1 text-white/45 sm:px-3">ISBN {{ $book->isbn }}</span>
                        @endif
                        @foreach ($book->genres as $genre)
                            <span class="rounded-full bg-white/10 px-2.5 py-1 sm:px-3">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    @if ($book->description)
                        <p class="mt-4 hidden max-w-3xl text-base leading-relaxed text-white/70 sm:mt-6 sm:block">{{ $book->description }}</p>
                    @endif
                </div>
            </div>

            <div class="-mx-4 mt-6 overflow-hidden bg-black/40 sm:mx-0 sm:mt-12 sm:rounded-3xl sm:ring-1 sm:ring-white/10">
                @if ($playlist->isNotEmpty())
                    <template x-if="current?.readable && current?.extension === 'pdf'">
                        <iframe :src="current?.src" class="aspect-[3/4] w-full bg-white sm:min-h-[40rem]" title="PDF"></iframe>
                    </template>
                    <template x-if="current?.readable && current?.extension === 'txt'">
                        <iframe :src="current?.src" class="min-h-[24rem] w-full bg-white sm:min-h-[40rem]" title="Текст"></iframe>
                    </template>
                    <div x-show="current && !current.readable" class="px-5 py-10 text-center">
                        <p class="text-white/60">Этот формат браузер не откроет (<span class="uppercase" x-text="current?.extension"></span>).</p>
                        <a :href="current?.src" class="mt-4 inline-flex min-h-11 items-center text-sm text-white underline" download>Скачать файл</a>
                    </div>
                @else
                    <div class="flex min-h-32 items-center justify-center px-6 py-10 text-center text-white/40">
                        К этой книге ещё не добавлены файлы.
                    </div>
                @endif
            </div>

            @if ($book->description)
                <p class="mt-6 text-sm leading-relaxed text-white/70 sm:hidden">{{ $book->description }}</p>
            @endif

            @if ($playlist->isNotEmpty())
                <div class="mt-8 sm:mt-10">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-white/40 sm:text-sm">Файлы</h2>
                    <div class="mt-3 grid gap-2 sm:gap-3 md:grid-cols-2">
                        @foreach ($playlist as $item)
                            <button
                                type="button"
                                class="min-h-14 rounded-2xl border p-3 text-left transition sm:p-4"
                                :class="currentId === {{ $item['id'] }} ? 'border-white/40 bg-white/10' : 'border-white/10 bg-white/5 hover:border-white/20 hover:bg-white/10'"
                                @click="select({{ $item['id'] }})"
                            >
                                <div class="font-medium text-white">{{ $item['title'] }}</div>
                                <div class="mt-1 text-xs uppercase text-white/40">{{ $item['extension'] }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
