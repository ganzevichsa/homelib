<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $book->title }}
            </h2>
            <a href="{{ route('admin.books') }}" class="text-sm text-gray-500 underline">К списку</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'book-created')
                <p class="text-sm text-green-600 dark:text-green-400">Карточка сохранена. Теперь можно добавить файлы.</p>
            @elseif (session('status') === 'book-updated')
                <p class="text-sm text-green-600 dark:text-green-400">Карточка обновлена.</p>
            @elseif (session('status') === 'book-file-created')
                <p class="text-sm text-green-600 dark:text-green-400">Файл добавлен.</p>
            @elseif (session('status') === 'book-file-deleted')
                <p class="text-sm text-green-600 dark:text-green-400">Файл убран. На диске он остался.</p>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Карточка</h3>

                    <form method="POST" action="{{ route('admin.books.update', $book) }}" class="mt-6 space-y-4" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        @include('admin.books.partials.fields', [
                            'title' => $book->getTranslation('title', \App\Enums\Locale::Ru->value, false),
                            'author' => $book->author,
                            'originalTitle' => $book->original_title,
                            'year' => $book->year,
                            'isbn' => $book->isbn,
                            'description' => $book->getTranslation('description', \App\Enums\Locale::Ru->value, false),
                            'selectedGenreIds' => $book->genres->pluck('id')->all(),
                        ])

                        <x-primary-button>Сохранить карточку</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-medium">Файлы</h3>
                        <a href="{{ route('admin.books.files.create', $book) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            Добавить файл
                        </a>
                    </div>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Том или формат — отдельная запись. Файлы лежат в папке books, загрузки нет.
                    </p>

                    <div class="mt-6 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($book->files as $file)
                            <div class="flex flex-col gap-3 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="font-medium">{{ $file->title }}</div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $file->filename }}</div>
                                </div>

                                <form method="POST" action="{{ route('admin.books.files.destroy', [$book, $file]) }}" onsubmit="return confirm('Убрать файл? На диске он останется.')">
                                    @csrf
                                    @method('delete')
                                    <x-danger-button>Убрать</x-danger-button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Файлов пока нет.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
