<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Добавить альбом
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Сначала карточка альбома — название и исполнитель, например «The Dark Side of the Moon».
                        Треки добавишь после сохранения, по одному.
                    </p>

                    <form method="POST" action="{{ route('admin.music.store') }}" class="mt-6 space-y-4" enctype="multipart/form-data">
                        @csrf

                        @include('admin.music.partials.fields')

                        <div class="flex items-center gap-4">
                            <x-primary-button>Сохранить</x-primary-button>
                            <a href="{{ route('admin.music') }}" class="text-sm text-gray-500 underline">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
