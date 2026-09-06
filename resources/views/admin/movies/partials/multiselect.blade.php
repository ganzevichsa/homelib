@php
    $selected = array_map('intval', old($name, $selectedIds ?? []));
@endphp

<div x-data="{ q: '' }">
    <x-input-label :value="$label" />
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Можно выбрать несколько.</p>

    <x-text-input type="search" class="mt-2 block w-full" placeholder="Поиск" x-model="q" />

    <div class="mt-2 max-h-48 overflow-y-auto rounded-md border border-gray-200 p-3 dark:border-gray-700">
        @forelse ($options as $option)
            <label
                class="flex items-center gap-2 py-1 text-sm"
                x-show="{{ \Illuminate\Support\Js::from($option->name) }}.toLowerCase().includes(q.toLowerCase())"
            >
                <input
                    type="checkbox"
                    name="{{ $name }}[]"
                    value="{{ $option->id }}"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm"
                    @checked(in_array($option->id, $selected, true))
                >
                <span>{{ $option->name }}</span>
            </label>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">Список пуст. Выполни php artisan db:seed.</p>
        @endforelse
    </div>

    <x-input-error class="mt-2" :messages="$errors->get($name)" />
    <x-input-error class="mt-2" :messages="$errors->get($name.'.*')" />
</div>
