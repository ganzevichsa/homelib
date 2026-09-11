<div>
    <x-input-label for="title" value="Название" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $title ?? '')" required />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div>
    <x-input-label for="original_title" value="Оригинальное название" />
    <x-text-input id="original_title" name="original_title" type="text" class="mt-1 block w-full" :value="old('original_title', $originalTitle ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('original_title')" />
</div>

<div>
    <x-input-label for="year" value="Год" />
    <x-text-input id="year" name="year" type="number" class="mt-1 block w-full" :value="old('year', $year ?? '')" min="1870" max="2100" />
    <x-input-error class="mt-2" :messages="$errors->get('year')" />
</div>

<div>
    <x-input-label for="poster" value="Постер" />
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Картинка для карточки в библиотеке. jpg, png или webp.</p>
    @if (isset($series) && $series->hasPoster())
        <div class="mt-3 flex items-end gap-4">
            <div class="h-40 w-28 overflow-hidden rounded-lg ring-1 ring-gray-200 dark:ring-gray-700">
                <x-series-cover :series="$series" />
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <input type="checkbox" name="remove_poster" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm">
                Убрать постер
            </label>
        </div>
    @endif
    <input id="poster" name="poster" type="file" accept="image/jpeg,image/png,image/webp" class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-3 file:py-2 file:text-xs file:font-semibold file:uppercase file:tracking-widest file:text-white dark:text-gray-400 dark:file:bg-gray-200 dark:file:text-gray-800">
    <x-input-error class="mt-2" :messages="$errors->get('poster')" />
</div>

<div>
    <x-input-label for="description" value="Описание" />
    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $description ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

@include('admin.movies.partials.multiselect', [
    'label' => 'Жанры',
    'name' => 'genre_ids',
    'options' => $genres,
    'selectedIds' => $selectedGenreIds ?? [],
])

@include('admin.movies.partials.multiselect', [
    'label' => 'Страны',
    'name' => 'country_ids',
    'options' => $countries,
    'selectedIds' => $selectedCountryIds ?? [],
])
