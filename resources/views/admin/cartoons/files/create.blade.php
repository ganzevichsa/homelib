<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Файл для «{{ $cartoon->title }}»
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Заполни карточку части, например «Король Лев (1994)».
                        Файл не загружается — начни вводить имя уже лежащего в
                        <span class="font-mono break-all">{{ $directory }}</span>
                    </p>

                    <form method="POST" action="{{ route('admin.cartoons.files.store', $cartoon) }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Название" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required placeholder="Король Лев (1994)" />
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

                        <div
                            x-data="{
                                url: {{ \Illuminate\Support\Js::from(route('admin.cartoons.files.search')) }},
                                query: {{ \Illuminate\Support\Js::from(old('filename', '')) }},
                                filename: {{ \Illuminate\Support\Js::from(old('filename', '')) }},
                                results: [],
                                open: false,
                                async search() {
                                    if (this.query.length < 2) {
                                        this.results = [];
                                        this.open = false;
                                        return;
                                    }

                                    const response = await fetch(this.url + '?q=' + encodeURIComponent(this.query), {
                                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                        credentials: 'same-origin',
                                    });
                                    const data = await response.json();
                                    this.results = data.filenames;
                                    this.open = true;
                                },
                                pick(name) {
                                    this.filename = name;
                                    this.query = name;
                                    this.open = false;
                                }
                            }"
                            class="relative"
                        >
                            <x-input-label for="filename_search" value="Файл" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Введи часть имени — покажем совпадения. Загрузки нет.</p>

                            <input type="hidden" name="filename" x-model="filename">

                            <x-text-input
                                id="filename_search"
                                type="search"
                                class="mt-1 block w-full"
                                placeholder="Например Lion"
                                x-model="query"
                                x-on:input.debounce.300ms="search()"
                                x-on:focus="if (results.length) open = true"
                                autocomplete="off"
                            />

                            <div
                                x-show="open && results.length"
                                x-cloak
                                class="absolute z-10 mt-1 w-full overflow-hidden rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
                            >
                                <template x-for="name in results" :key="name">
                                    <button
                                        type="button"
                                        class="block w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
                                        x-text="name"
                                        @click="pick(name)"
                                    ></button>
                                </template>
                            </div>

                            <p x-show="filename" class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                Выбран: <span class="font-mono break-all" x-text="filename"></span>
                            </p>

                            <p x-show="query.length >= 2 && open && results.length === 0" class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                Ничего не найдено. Проверь имя файла в папке.
                            </p>

                            <x-input-error class="mt-2" :messages="$errors->get('filename')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Добавить файл</x-primary-button>
                            <a href="{{ route('admin.cartoons.edit', $cartoon) }}" class="text-sm text-gray-500 underline">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
