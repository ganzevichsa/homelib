@props(['game'])

@if ($game->hasPoster())
    <img
        src="{{ route('library.game.poster', $game) }}"
        alt="{{ $game->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-indigo-800/70 via-slate-900 to-rose-900/40 p-4']) }}>
        <div class="line-clamp-3 text-sm font-medium text-white/80">{{ $game->title }}</div>
    </div>
@endif
