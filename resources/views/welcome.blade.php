<x-public-layout title="Homelib">
    <div class="flex min-h-[calc(100svh-5rem)] items-center justify-center px-4 py-10 sm:px-8 sm:py-12">
        <div class="flex max-w-5xl flex-wrap items-center justify-center gap-x-4 gap-y-3 text-center sm:gap-x-8 sm:gap-y-6">
            @foreach ($categories as $category)
                <a
                    href="{{ route('library.show', $category) }}"
                    class="{{ $category->homeSize() }} inline-flex min-h-11 items-center px-1 font-semibold tracking-tight text-white/80 transition duration-300 hover:scale-[1.04] hover:text-white focus:outline-none focus-visible:text-white"
                >
                    {{ $category->label() }}
                </a>
            @endforeach
        </div>
    </div>
</x-public-layout>
