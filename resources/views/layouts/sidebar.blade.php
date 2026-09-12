<aside
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-gray-200 bg-white transition-transform duration-200 dark:border-gray-800 dark:bg-gray-900 lg:static lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-16 shrink-0 items-center px-5">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold tracking-[0.2em] uppercase text-gray-800 dark:text-gray-100">
            Homelib
        </a>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-2">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-sidebar-link>

        <p class="px-3 pt-5 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            Категории
        </p>

        <x-sidebar-link :href="route('admin.movies')" :active="request()->routeIs('admin.movies*')">Фильмы</x-sidebar-link>
        <x-sidebar-link :href="route('admin.series')" :active="request()->routeIs('admin.series*')">Сериалы</x-sidebar-link>
        <x-sidebar-link :href="route('admin.cartoons')" :active="request()->routeIs('admin.cartoons*')">Мультфильмы</x-sidebar-link>
        <x-sidebar-link :href="route('admin.animated-series')" :active="request()->routeIs('admin.animated-series*')">Мультсериалы</x-sidebar-link>
        <x-sidebar-link :href="route('admin.music')" :active="request()->routeIs('admin.music*')">Музыка</x-sidebar-link>
        <x-sidebar-link :href="route('admin.audiobooks')" :active="request()->routeIs('admin.audiobooks')">Аудиокниги</x-sidebar-link>
        <x-sidebar-link :href="route('admin.books')" :active="request()->routeIs('admin.books')">Книги</x-sidebar-link>
        <x-sidebar-link :href="route('admin.games')" :active="request()->routeIs('admin.games')">Игры</x-sidebar-link>
        <x-sidebar-link :href="route('admin.gallery')" :active="request()->routeIs('admin.gallery')">Галерея</x-sidebar-link>
        <x-sidebar-link :href="route('admin.files')" :active="request()->routeIs('admin.files')">Файлы</x-sidebar-link>
        <x-sidebar-link :href="route('admin.documents')" :active="request()->routeIs('admin.documents')">Документы</x-sidebar-link>
        <x-sidebar-link :href="route('admin.other')" :active="request()->routeIs('admin.other')">Остальное</x-sidebar-link>
    </nav>

    <div class="border-t border-gray-200 p-3 dark:border-gray-800">
        <div class="px-3 py-2 text-sm font-medium text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</div>
        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">Профиль</x-sidebar-link>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-100">
                Выйти
            </button>
        </form>
    </div>
</aside>
