<x-public-layout :title="$type->label()">
    <div class="mx-auto max-w-5xl px-6 py-16">
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-semibold tracking-tight sm:text-6xl">{{ $type->label() }}</h1>
            <a href="{{ route('home') }}" class="mt-4 inline-block text-sm text-white/40 hover:text-white">На главную</a>
        </div>

        @forelse ($movies as $movie)
            <a href="{{ route('library.movie', $movie) }}" class="block border-b border-white/10 py-5 text-white/80 transition hover:text-white">
                <div class="text-2xl font-medium tracking-tight">{{ $movie->title }}</div>
                <div class="mt-1 text-sm text-white/40">
                    @if ($movie->year){{ $movie->year }}@endif
                    · {{ $movie->files->count() }} файлов
                </div>
            </a>
        @empty
            <p class="text-center text-white/45">Пока пусто</p>
        @endforelse
    </div>
</x-public-layout>
