@props(['album'])

@php
    $cover = $album->hasPoster() ? null : $album->coverItem();
@endphp

@if ($album->hasPoster())
    <img
        src="{{ route('library.gallery.poster', $album) }}"
        alt="{{ $album->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@elseif ($cover)
    <img
        src="{{ route('library.gallery.stream', [$album, $cover]) }}"
        alt="{{ $album->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-sky-800/70 via-slate-900 to-fuchsia-900/40 p-4']) }}>
        <div class="line-clamp-3 text-sm font-medium text-white/80">{{ $album->title }}</div>
    </div>
@endif
