<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Мультфильмы
            </h2>
            <a href="{{ route('admin.cartoons.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                Добавить
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status') === 'cartoon-deleted')
                <p class="text-sm text-green-600 dark:text-green-400">Запись удалена. Файлы на диске остались.</p>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @forelse ($cartoons as $cartoon)
                        <div class="flex flex-col gap-3 py-4 border-t border-gray-200 dark:border-gray-700 first:border-t-0 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('admin.cartoons.edit', $cartoon) }}" class="hover:underline">
                                <div class="font-medium">{{ $cartoon->title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @if ($cartoon->year){{ $cartoon->year }} · @endif{{ $cartoon->files->count() }} файлов
                                    @if ($cartoon->genres->isNotEmpty())
                                        · {{ $cartoon->genres->pluck('name')->join(', ') }}
                                    @endif
                                    @if ($cartoon->countries->isNotEmpty())
                                        · {{ $cartoon->countries->pluck('name')->join(', ') }}
                                    @endif
                                </div>
                                @if ($cartoon->files->isNotEmpty())
                                    <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                        {{ $cartoon->files->pluck('title')->join(', ') }}
                                    </div>
                                @endif
                            </a>

                            <form method="POST" action="{{ route('admin.cartoons.destroy', $cartoon) }}" onsubmit="return confirm('Удалить карточку? Файлы на диске останутся.')">
                                @csrf
                                @method('delete')
                                <x-danger-button>Удалить</x-danger-button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">Пока пусто</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
