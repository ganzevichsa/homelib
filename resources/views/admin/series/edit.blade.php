<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $series->title }}
            </h2>
            <a href="{{ route('admin.series') }}" class="text-sm text-gray-500 underline">К списку</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'series-created')
                <p class="text-sm text-green-600 dark:text-green-400">Карточка сохранена. Теперь можно добавить сезоны.</p>
            @elseif (session('status') === 'series-updated')
                <p class="text-sm text-green-600 dark:text-green-400">Карточка обновлена.</p>
            @elseif (session('status') === 'season-created')
                <p class="text-sm text-green-600 dark:text-green-400">Сезон добавлен.</p>
            @elseif (session('status') === 'season-deleted')
                <p class="text-sm text-green-600 dark:text-green-400">Сезон удалён. Файлы на диске остались.</p>
            @elseif (session('status') === 'episode-created')
                <p class="text-sm text-green-600 dark:text-green-400">Серия добавлена.</p>
            @elseif (session('status') === 'episode-deleted')
                <p class="text-sm text-green-600 dark:text-green-400">Серия убрана. Файл на диске остался.</p>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Карточка</h3>

                    <form method="POST" action="{{ route('admin.series.update', $series) }}" class="mt-6 space-y-4" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        @include('admin.series.partials.fields', [
                            'title' => $series->getTranslation('title', \App\Enums\Locale::Ru->value, false),
                            'originalTitle' => $series->original_title,
                            'year' => $series->year,
                            'description' => $series->getTranslation('description', \App\Enums\Locale::Ru->value, false),
                            'selectedGenreIds' => $series->genres->pluck('id')->all(),
                            'selectedCountryIds' => $series->countries->pluck('id')->all(),
                        ])

                        <x-primary-button>Сохранить карточку</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium">Сезоны</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Сначала сезон, потом серии внутри него. Файлы лежат в папке series, загрузки нет.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.series.seasons.store', $series) }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                        @csrf
                        <div class="sm:w-28">
                            <x-input-label for="number" value="Номер" />
                            <x-text-input id="number" name="number" type="number" class="mt-1 block w-full" :value="old('number')" min="1" required />
                        </div>
                        <div class="flex-1">
                            <x-input-label for="season_title" value="Название сезона" />
                            <x-text-input id="season_title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" placeholder="необязательно" />
                        </div>
                        <x-primary-button>Добавить сезон</x-primary-button>
                    </form>
                    <x-input-error :messages="$errors->get('number')" />

                    @forelse ($series->seasons as $season)
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="font-medium">{{ $season->label() }}</div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.series.episodes.create', [$series, $season]) }}" class="text-sm underline text-gray-600 dark:text-gray-300">Добавить серию</a>
                                    <form method="POST" action="{{ route('admin.series.seasons.destroy', [$series, $season]) }}" onsubmit="return confirm('Удалить сезон и все его серии из карточки?')">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button>Удалить сезон</x-danger-button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($season->episodes as $episode)
                                    <div class="flex flex-col gap-3 py-3 first:pt-0 last:pb-0 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <div class="font-medium">{{ $episode->number }}. {{ $episode->title }}</div>
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $episode->filename }}</div>
                                            @if ($episode->description)
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $episode->description }}</p>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('admin.series.episodes.destroy', [$series, $season, $episode]) }}" onsubmit="return confirm('Убрать серию? Файл на диске останется.')">
                                            @csrf
                                            @method('delete')
                                            <x-danger-button>Убрать</x-danger-button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Серий пока нет.</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Сезонов пока нет.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
