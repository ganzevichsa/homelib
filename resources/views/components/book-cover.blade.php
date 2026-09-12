@props(['book'])

@if ($book->hasPoster())
    <img
        src="{{ route('library.book.poster', $book) }}"
        alt="{{ $book->title }}"
        {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    >
@else
    <div {{ $attributes->merge(['class' => 'flex h-full w-full items-end bg-gradient-to-br from-amber-800/70 via-slate-900 to-emerald-900/40 p-4']) }}>
        <div>
            <div class="line-clamp-3 text-sm font-medium text-white/80">{{ $book->title }}</div>
            <div class="mt-1 line-clamp-1 text-xs text-white/50">{{ $book->author }}</div>
        </div>
    </div>
@endif
