<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Фильмы
            </h2>
            <a href="{{ route('admin.movies.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                Добавить
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status') === 'movie-created')
                <p class="text-sm text-green-600 dark:text-green-400">Фильм добавлен.</p>
            @elseif (session('status') === 'movie-deleted')
                <p class="text-sm text-green-600 dark:text-green-400">Запись удалена. Файл на диске остался.</p>
            @endif

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Файлы лежат в <span class="font-mono break-all">{{ $directory }}</span>
                @if (count($availableFiles))
                    · свободно: {{ count($availableFiles) }}
                @endif
            </p>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @forelse ($movies as $movie)
                        <div class="flex flex-col gap-3 py-4 border-t border-gray-200 dark:border-gray-700 first:border-t-0 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="font-medium">{{ $movie->title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @if ($movie->year){{ $movie->year }} · @endif{{ $movie->files->count() }} файлов
                                </div>
                                <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    {{ $movie->files->pluck('title')->join(', ') }}
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}" onsubmit="return confirm('Удалить запись? Файл останется в папке.')">
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
