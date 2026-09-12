@props(['audiobook'])

@if ($audiobook->hasPoster())
    <img
        src="{{ route('library.audiobook.poster', $audiobook) }}"
        alt="{{ $audiobook->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-emerald-800/70 via-slate-900 to-amber-900/40 p-4']) }}>
        <div>
            <div class="line-clamp-2 text-sm font-medium text-white/80">{{ $audiobook->title }}</div>
            <div class="mt-1 line-clamp-1 text-xs text-white/50">{{ $audiobook->author }}</div>
        </div>
    </div>
@endif
