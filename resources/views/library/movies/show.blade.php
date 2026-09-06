<x-public-layout :title="$movie->title">
    <div class="mx-auto max-w-3xl px-6 py-20">
        <a href="{{ route('library.show', \App\Enums\MediaType::Movie) }}" class="text-sm text-white/40 hover:text-white">← Фильмы</a>

        <h1 class="mt-8 text-4xl font-semibold tracking-tight sm:text-6xl">{{ $movie->title }}</h1>

        @if ($movie->original_title)
            <p class="mt-3 text-lg text-white/50">{{ $movie->original_title }}</p>
        @endif

        @if ($movie->year || $movie->genres->isNotEmpty() || $movie->countries->isNotEmpty())
            <p class="mt-4 text-sm text-white/40">
                @if ($movie->year){{ $movie->year }}@endif
                @if ($movie->genres->isNotEmpty())
                    @if ($movie->year) · @endif{{ $movie->genres->pluck('name')->join(', ') }}
                @endif
                @if ($movie->countries->isNotEmpty())
                    @if ($movie->year || $movie->genres->isNotEmpty()) · @endif{{ $movie->countries->pluck('name')->join(', ') }}
                @endif
            </p>
        @endif

        @if ($movie->description)
            <p class="mt-8 text-base leading-relaxed text-white/70">{{ $movie->description }}</p>
        @endif

        <div class="mt-12 space-y-3">
            @foreach ($movie->files as $file)
                <div class="border-b border-white/10 py-4">
                    <div class="text-lg text-white/85">{{ $file->title ?: $file->filename }}</div>
                    @if ($file->year)
                        <div class="mt-1 text-sm text-white/40">{{ $file->year }}</div>
                    @endif
                    @if ($file->description)
                        <p class="mt-2 text-sm leading-relaxed text-white/60">{{ $file->description }}</p>
                    @endif
                    <div class="mt-1 text-xs text-white/35">{{ $file->filename }}</div>
                </div>
            @endforeach
        </div>
    </div>
</x-public-layout>
