<x-public-layout :title="$type->label()">
    <div class="flex min-h-[calc(100vh-5.5rem)] flex-col items-center justify-center px-6 text-center">
        <h1 class="text-4xl font-semibold tracking-tight sm:text-6xl">{{ $type->label() }}</h1>
        <p class="mt-5 text-white/45">Пока пусто</p>
        <a href="{{ route('home') }}" class="mt-10 text-sm text-white/50 underline decoration-white/20 underline-offset-4 hover:text-white">
            На главную
        </a>
    </div>
</x-public-layout>
