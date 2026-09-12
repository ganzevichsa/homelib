# Homelib для Android

Один APK для телефона, планшета, Android TV и Android-проектора. Фильмы остаются на компьютере: в приложении один раз указываешь IP, дальше открывается иконка.

## Собрать APK

1. Поставь [Android Studio](https://developer.android.com/studio).
2. File → Open → папка `apps/android`.
3. Дождись Gradle Sync.
4. Build → Build Bundle(s) / APK(s) → Build APK(s).
5. Готовый файл: `apps/android/app/build/outputs/apk/debug/app-debug.apk`.

Скопируй APK на устройство (флешка, Discord, `adb install app-debug.apk`) и установи.

## Первый запуск

В поле адреса введи IP компьютера, например `192.168.1.10` или `http://192.168.1.10`. Homelib на ПК должен быть включён, телефон и ТВ — в той же сети.

IP компьютера в Windows: `ipconfig` → IPv4.

Сменить адрес: шестерёнка в углу или кнопка Menu на пульте.

## Чтобы IP открывал Homelib

Скопируй `laragon-vhost.conf` в `C:\laragon\etc\apache2\sites-enabled\homelib-lan.conf` и перезапусти Apache в Laragon.

Проверка с компьютера: в браузере открой `http://ТВОЙ-IP/` — должна быть главная Homelib, не список папок Laragon.
