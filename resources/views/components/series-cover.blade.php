@props(['series'])

@if ($series->hasPoster())
    <img
        src="{{ route('library.series.poster', $series) }}"
        alt="{{ $series->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-violet-800/70 via-slate-900 to-cyan-900/40 p-4']) }}>
        <div>
            <div class="mt-2 line-clamp-3 text-sm font-medium text-white/70">{{ $series->title }}</div>
        </div>
    </div>
@endif
