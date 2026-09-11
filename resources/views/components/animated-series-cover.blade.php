@props(['animatedSeries'])

@if ($animatedSeries->hasPoster())
    <img
        src="{{ route('library.animated-series.poster', $animatedSeries) }}"
        alt="{{ $animatedSeries->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-emerald-800/70 via-slate-900 to-sky-900/40 p-4']) }}>
        <div>
            <div class="mt-2 line-clamp-3 text-sm font-medium text-white/70">{{ $animatedSeries->title }}</div>
        </div>
    </div>
@endif
