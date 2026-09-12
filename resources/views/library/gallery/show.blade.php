<x-public-layout :title="$album->title">
    <div
        class="relative"
        x-data="{
            items: {{ \Illuminate\Support\Js::from($items) }},
            currentId: null,
            get current() {
                return this.items.find((item) => item.id === this.currentId) ?? null;
            },
            get index() {
                return this.items.findIndex((item) => item.id === this.currentId);
            },
            open(id) {
                this.currentId = id;
            },
            close() {
                this.currentId = null;
            },
            next() {
                const next = this.items[this.index + 1];
                if (next) {
                    this.currentId = next.id;
                }
            },
            prev() {
                const prev = this.items[this.index - 1];
                if (prev) {
                    this.currentId = prev.id;
                }
            }
        }"
        x-on:keydown.escape.window="close()"
        x-on:keydown.arrow-right.window="if (currentId) next()"
        x-on:keydown.arrow-left.window="if (currentId) prev()"
    >
        <div class="relative mx-auto max-w-6xl px-4 pb-24 pt-3 sm:px-8 sm:pt-4">
            <a href="{{ route('library.show', \App\Enums\MediaType::Gallery) }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">← Галерея</a>

            <div class="mt-4 sm:mt-8">
                <h1 class="text-2xl font-extrabold tracking-tight sm:text-4xl lg:text-6xl">{{ $album->title }}</h1>
                @if ($album->year)
                    <p class="mt-2 text-sm text-white/45">{{ $album->year }}</p>
                @endif
                @if ($album->description)
                    <p class="mt-4 max-w-3xl text-sm leading-relaxed text-white/70 sm:text-base">{{ $album->description }}</p>
                @endif
            </div>

            @if ($items->isEmpty())
                <p class="mt-10 text-white/40">В альбоме пока нет фото и видео.</p>
            @else
                <div class="mt-8 grid grid-cols-2 gap-2 sm:mt-10 sm:grid-cols-3 sm:gap-3 md:grid-cols-4">
                    @foreach ($items as $item)
                        <button
                            type="button"
                            class="group relative aspect-square overflow-hidden rounded-xl bg-white/5 ring-1 ring-white/10"
                            @click="open({{ $item['id'] }})"
                        >
                            @if ($item['image'])
                                <img src="{{ $item['src'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-800 to-black text-white/60">
                                    <span class="text-xs uppercase">{{ $item['extension'] }}</span>
                                </div>
                            @endif
                            @if ($item['video'])
                                <span class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/25 text-white">▶</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div
            x-show="current"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
            @click.self="close()"
        >
            <button type="button" class="absolute left-3 top-3 text-sm text-white/70 hover:text-white" @click="close()">Закрыть</button>

            <button type="button" class="absolute left-2 top-1/2 hidden -translate-y-1/2 px-3 py-8 text-white/60 hover:text-white sm:block" @click="prev()">‹</button>
            <button type="button" class="absolute right-2 top-1/2 hidden -translate-y-1/2 px-3 py-8 text-white/60 hover:text-white sm:block" @click="next()">›</button>

            <div class="max-h-[90vh] w-full max-w-5xl">
                <template x-if="current?.image">
                    <img :src="current?.src" :alt="current?.title" class="mx-auto max-h-[80vh] w-auto max-w-full object-contain">
                </template>
                <template x-if="current?.video && current?.playable">
                    <video :src="current?.src" class="mx-auto max-h-[80vh] w-full" controls autoplay></video>
                </template>
                <div x-show="current && current.video && !current.playable" class="px-6 py-10 text-center text-white/70">
                    Этот формат браузер не откроет. Скачай файл.
                    <a :href="current?.src" class="mt-4 block underline" download>Скачать</a>
                </div>
                <p class="mt-3 text-center text-sm text-white/60" x-text="current?.title"></p>
            </div>
        </div>
    </div>
</x-public-layout>
