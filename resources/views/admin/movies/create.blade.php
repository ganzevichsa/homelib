<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Добавить фильм
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Одна карточка — несколько файлов. Например «Матрица», а файлы:
                        Matrix 1999, Matrix Reloaded, Matrix Revolutions.
                        Положи их в <span class="font-mono break-all">{{ $directory }}</span>
                    </p>

                    @if (count($files) === 0)
                        <p class="mt-6 text-sm text-amber-600 dark:text-amber-400">
                            Свободных файлов нет. Добавь видео в папку.
                        </p>
                        <a href="{{ route('admin.movies') }}" class="mt-4 inline-block text-sm underline text-gray-500">Назад</a>
                    @else
                        <form method="POST" action="{{ route('admin.movies.store') }}" class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="title_ru" value="Название (RU)" />
                                <x-text-input id="title_ru" name="title_ru" type="text" class="mt-1 block w-full" :value="old('title_ru')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title_ru')" />
                            </div>

                            <div>
                                <x-input-label for="title_en" value="Название (EN)" />
                                <x-text-input id="title_en" name="title_en" type="text" class="mt-1 block w-full" :value="old('title_en')" />
                                <x-input-error class="mt-2" :messages="$errors->get('title_en')" />
                            </div>

                            <div>
                                <x-input-label for="original_title" value="Оригинальное название" />
                                <x-text-input id="original_title" name="original_title" type="text" class="mt-1 block w-full" :value="old('original_title')" />
                                <x-input-error class="mt-2" :messages="$errors->get('original_title')" />
                            </div>

                            <div>
                                <x-input-label for="year" value="Год" />
                                <x-text-input id="year" name="year" type="number" class="mt-1 block w-full" :value="old('year')" min="1870" max="2100" />
                                <x-input-error class="mt-2" :messages="$errors->get('year')" />
                            </div>

                            <div>
                                <x-input-label for="description_ru" value="Описание (RU)" />
                                <textarea id="description_ru" name="description_ru" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description_ru') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description_ru')" />
                            </div>

                            <div>
                                <x-input-label for="description_en" value="Описание (EN)" />
                                <textarea id="description_en" name="description_en" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description_en') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description_en')" />
                            </div>

                            <div>
                                <x-input-label value="Файлы" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Можно выбрать несколько. Подпись — как файл будет называться в карточке.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('files')" />

                                <div class="mt-3 space-y-3">
                                    @foreach ($files as $file)
                                        <label class="flex flex-col gap-2 rounded-lg border border-gray-200 p-3 dark:border-gray-700 sm:flex-row sm:items-center">
                                            <span class="flex items-center gap-2 sm:w-1/2">
                                                <input type="checkbox" name="files[]" value="{{ $file }}" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600" @checked(in_array($file, old('files', []), true))>
                                                <span class="text-sm break-all">{{ $file }}</span>
                                            </span>
                                            <x-text-input name="file_titles[{{ $file }}]" type="text" class="block w-full sm:w-1/2" :value="old('file_titles.'.$file)" placeholder="Название части, например Матрица 1999" />
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Сохранить</x-primary-button>
                                <a href="{{ route('admin.movies') }}" class="text-sm text-gray-500 underline">Отмена</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
