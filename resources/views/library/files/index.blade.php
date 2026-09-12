<x-public-layout :title="$type->label()">
    <div class="mx-auto max-w-7xl px-4 pb-24 pt-4 sm:px-8 sm:pt-6">
        <div class="mb-6 flex flex-col gap-2 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-white/40 sm:text-xs">Библиотека</p>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight sm:mt-2 sm:text-5xl">{{ $type->label() }}</h1>
            </div>
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-sm text-white/40 transition hover:text-white">На главную</a>
        </div>

        @if ($entries->isEmpty())
            <div class="rounded-2xl border border-white/10 bg-white/5 px-5 py-16 text-center sm:rounded-3xl sm:px-6 sm:py-20">
                <p class="text-lg text-white/50">Пока пусто</p>
                <p class="mt-2 text-sm text-white/30">Когда добавишь файлы в кабинете, они появятся здесь.</p>
            </div>
        @else
            <div class="grid gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                @foreach ($entries as $entry)
                    <a href="{{ route('library.file', $entry) }}" class="block rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-white/25 hover:bg-white/10 sm:p-5">
                        <div class="text-lg font-semibold text-white">{{ $entry->title }}</div>
                        <div class="mt-2 text-sm text-white/45">
                            {{ $entry->files->count() }} {{ $entry->files->count() === 1 ? 'файл' : 'файлов' }}
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-public-layout>
