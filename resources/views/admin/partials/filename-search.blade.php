<div
    x-data="{
        url: {{ \Illuminate\Support\Js::from($searchUrl) }},
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
        placeholder="Начни вводить имя"
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
            <button type="button" class="block w-full px-3 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-800" x-text="name" @click="pick(name)"></button>
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
