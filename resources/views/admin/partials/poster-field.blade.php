<div>
    <x-input-label for="poster" :value="$label ?? 'Обложка'" />
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">jpg, png или webp.</p>
    @if (isset($model) && $model->hasPoster())
        <div class="mt-3 flex items-end gap-4">
            <div class="{{ $previewClass ?? 'h-28 w-28' }} overflow-hidden rounded-lg ring-1 ring-gray-200 dark:ring-gray-700">
                {{ $preview }}
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <input type="checkbox" name="remove_poster" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm">
                Убрать
            </label>
        </div>
    @endif
    <input id="poster" name="poster" type="file" accept="image/jpeg,image/png,image/webp" class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-gray-800 file:px-3 file:py-2 file:text-xs file:font-semibold file:uppercase file:tracking-widest file:text-white dark:text-gray-400 dark:file:bg-gray-200 dark:file:text-gray-800">
    <x-input-error class="mt-2" :messages="$errors->get('poster')" />
</div>
