@props(['movie'])

@if ($movie->hasPoster())
    <img
        src="{{ route('library.movie.poster', $movie) }}"
        alt="{{ $movie->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-violet-800/70 via-slate-900 to-cyan-900/40 p-4']) }}>
        <div>
            <div class="text-4xl font-extrabold tracking-tight text-white/90">{{ mb_substr((string) $movie->title, 0, 1) }}</div>
            <div class="mt-2 line-clamp-3 text-sm font-medium text-white/70">{{ $movie->title }}</div>
        </div>
    </div>
@endif
