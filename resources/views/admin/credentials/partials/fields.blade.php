<div>
    <x-input-label for="title" value="Название" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $title ?? '')" required placeholder="Подключение на сайт ЕРА админ" />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div>
    <x-input-label for="login" value="Логин" />
    <x-text-input id="login" name="login" type="text" class="mt-1 block w-full" :value="old('login', $login ?? '')" placeholder="root" autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('login')" />
</div>

<div>
    <x-input-label for="password" :value="isset($credential) ? 'Пароль (оставь пустым, чтобы не менять)' : 'Пароль'" />
    <x-text-input id="password" name="password" type="text" class="mt-1 block w-full" :value="old('password')" autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('password')" />
</div>

<div>
    <x-input-label for="url" value="Ссылка" />
    <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" :value="old('url', $url ?? '')" placeholder="https://example.test" />
    <x-input-error class="mt-2" :messages="$errors->get('url')" />
</div>

<div>
    <x-input-label for="ip" value="IP" />
    <x-text-input id="ip" name="ip" type="text" class="mt-1 block w-full" :value="old('ip', $ip ?? '')" placeholder="192.168.2.1" />
    <x-input-error class="mt-2" :messages="$errors->get('ip')" />
</div>

<div>
    <x-input-label for="protocol" value="Протокол" />
    <x-text-input id="protocol" name="protocol" type="text" class="mt-1 block w-full" :value="old('protocol', $protocol ?? '')" placeholder="ssh" />
    <x-input-error class="mt-2" :messages="$errors->get('protocol')" />
</div>

<div>
    <x-input-label for="notes" value="Заметки" />
    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $notes ?? '') }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('notes')" />
</div>
