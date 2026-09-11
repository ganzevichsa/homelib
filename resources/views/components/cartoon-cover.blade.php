@props(['cartoon'])

@if ($cartoon->hasPoster())
    <img
        src="{{ route('library.cartoon.poster', $cartoon) }}"
        alt="{{ $cartoon->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-amber-800/70 via-slate-900 to-rose-900/40 p-4']) }}>
        <div>
            <div class="mt-2 line-clamp-3 text-sm font-medium text-white/70">{{ $cartoon->title }}</div>
        </div>
    </div>
@endif
