<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Файл для «{{ $movie->title }}»
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Заполни карточку части, например «Матрица (2012)».
                        Файл не загружается — выбери уже лежащий в
                        <span class="font-mono break-all">{{ $directory }}</span>
                    </p>

                    <form method="POST" action="{{ route('admin.movies.files.store', $movie) }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Название" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required placeholder="Матрица (2012)" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="year" value="Год" />
                            <x-text-input id="year" name="year" type="number" class="mt-1 block w-full" :value="old('year')" min="1870" max="2100" />
                            <x-input-error class="mt-2" :messages="$errors->get('year')" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Описание" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="filename" value="Файл" />
                            <select id="filename" name="filename" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Выбери файл</option>
                                @foreach ($filenames as $filename)
                                    <option value="{{ $filename }}" @selected(old('filename') === $filename)>{{ $filename }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('filename')" />

                            @if (count($filenames) === 0)
                                <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                    В папке нет видео, которые ещё не привязаны. Положи файл в каталог и обнови страницу.
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Добавить файл</x-primary-button>
                            <a href="{{ route('admin.movies.edit', $movie) }}" class="text-sm text-gray-500 underline">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
