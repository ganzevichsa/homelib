<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Файл для «{{ $entry->title }}»
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Файл не загружается — начни вводить имя уже лежащего в
                        <span class="font-mono break-all">{{ $directory }}</span>
                    </p>

                    <form method="POST" action="{{ route('admin.files.attachments.store', $entry) }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="title" value="Название" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required placeholder="PDF" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        @include('admin.partials.filename-search', ['searchUrl' => route('admin.files.attachments.search')])

                        <div class="flex items-center gap-4">
                            <x-primary-button>Добавить файл</x-primary-button>
                            <a href="{{ route('admin.files.edit', $entry) }}" class="text-sm text-gray-500 underline">Назад</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
