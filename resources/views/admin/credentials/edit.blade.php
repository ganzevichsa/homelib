<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $credential->title }}
            </h2>
            <a href="{{ route('admin.credentials') }}" class="text-sm text-gray-500 underline">К списку</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'created')
                <p class="text-sm text-green-600 dark:text-green-400">Запись сохранена.</p>
            @elseif (session('status') === 'updated')
                <p class="text-sm text-green-600 dark:text-green-400">Запись обновлена.</p>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div
                        x-data="{ show: false }"
                        class="mb-8 space-y-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    >
                        <div class="text-sm text-gray-500 dark:text-gray-400">Текущие данные</div>
                        @if ($credential->login)
                            <div><span class="text-gray-500 dark:text-gray-400">Логин:</span> {{ $credential->login }}</div>
                        @endif
                        @if ($credential->password)
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Пароль:</span>
                                <span class="font-mono" x-text="show ? {{ \Illuminate\Support\Js::from($credential->password) }} : '••••••••'"></span>
                                <button type="button" class="text-sm text-indigo-600 underline dark:text-indigo-400" @click="show = !show" x-text="show ? 'Скрыть' : 'Показать'"></button>
                            </div>
                        @endif
                        @if ($credential->url)
                            <div><span class="text-gray-500 dark:text-gray-400">Ссылка:</span> <a href="{{ $credential->url }}" class="underline break-all" target="_blank" rel="noreferrer">{{ $credential->url }}</a></div>
                        @endif
                        @if ($credential->ip)
                            <div><span class="text-gray-500 dark:text-gray-400">IP:</span> {{ $credential->ip }}</div>
                        @endif
                        @if ($credential->protocol)
                            <div><span class="text-gray-500 dark:text-gray-400">Протокол:</span> {{ $credential->protocol }}</div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.credentials.update', $credential) }}" class="space-y-4">
                        @csrf
                        @method('put')

                        @include('admin.credentials.partials.fields', [
                            'title' => $credential->title,
                            'login' => $credential->login,
                            'url' => $credential->url,
                            'ip' => $credential->ip,
                            'protocol' => $credential->protocol,
                            'notes' => $credential->notes,
                        ])

                        <x-primary-button>Сохранить</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
