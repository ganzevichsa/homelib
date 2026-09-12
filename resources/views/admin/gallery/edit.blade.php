<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $album->title }}
            </h2>
            <a href="{{ route('admin.gallery') }}" class="text-sm text-gray-500 underline">К списку</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'created')
                <p class="text-sm text-green-600 dark:text-green-400">Альбом сохранён. Теперь можно добавить фото и видео.</p>
            @elseif (session('status') === 'updated')
                <p class="text-sm text-green-600 dark:text-green-400">Карточка обновлена.</p>
            @elseif (session('status') === 'item-created')
                <p class="text-sm text-green-600 dark:text-green-400">Файл добавлен.</p>
            @elseif (session('status') === 'item-deleted')
                <p class="text-sm text-green-600 dark:text-green-400">Файл убран. На диске он остался.</p>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Карточка</h3>

                    <form method="POST" action="{{ route('admin.gallery.update', $album) }}" class="mt-6 space-y-4" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        @include('admin.gallery.partials.fields', [
                            'title' => $album->getTranslation('title', \App\Enums\Locale::Ru->value, false),
                            'year' => $album->year,
                            'description' => $album->getTranslation('description', \App\Enums\Locale::Ru->value, false),
                        ])

                        <x-primary-button>Сохранить карточку</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-medium">Фото и видео</h3>
                        <a href="{{ route('admin.gallery.items.create', $album) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                            Добавить файл
                        </a>
                    </div>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        В одном альбоме и фото, и видео. Файлы лежат в папке gallery, загрузки нет.
                    </p>

                    <div class="mt-6 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($album->items as $item)
                            <div class="flex flex-col gap-3 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="font-medium">{{ $item->label() }}</div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->filename }}
                                        · {{ $item->isVideo() ? 'видео' : 'фото' }}
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.gallery.items.destroy', [$album, $item]) }}" onsubmit="return confirm('Убрать файл? На диске он останется.')">
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
