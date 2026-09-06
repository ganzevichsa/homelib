<x-public-layout title="Homelib">
    <div class="flex min-h-[calc(100vh-5.5rem)] items-center justify-center px-5 py-12 sm:px-8">
        <div class="flex max-w-5xl flex-wrap items-center justify-center gap-x-5 gap-y-4 text-center sm:gap-x-8 sm:gap-y-6">
            @foreach ($categories as $category)
                <a
                    href="{{ route('library.show', $category) }}"
                    class="{{ $category->homeSize() }} font-semibold tracking-tight text-white/80 transition duration-300 hover:text-white hover:scale-[1.04] focus:outline-none focus-visible:text-white"
                >
                    {{ $category->label() }}
                </a>
            @endforeach
        </div>
    </div>
</x-public-layout>
