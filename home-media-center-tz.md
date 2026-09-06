# Техническое задание — Домашняя цифровая библиотека / Home Media Center

## 1. Общая идея проекта

Создать собственное приложение для хранения, каталогизации и просмотра личной цифровой библиотеки.

Система должна работать как единый персональный цифровой центр, в котором пользователь хранит:

- фильмы;
- сериалы;
- мультфильмы;
- мультсериалы;
- музыку;
- книги;
- аудиокниги;
- документальные фильмы;
- игры;
- обычные файлы;
- фотографии;
- личные видео;
- альбомы;
- документы;
- зашифрованные пароли;
- другие типы цифрового контента.

Система должна иметь Web-интерфейс, адаптивную мобильную версию и отдельный TV Mode для Smart TV / Android TV / Google TV / TV Box.

Главная идея:

> Один сервер → одна библиотека → доступ с компьютера, телефона и телевизора.

---

## 2. Платформы

### Desktop
- Windows
- macOS
- Linux

### Mobile
На первом этапе — качественный responsive Web UI.

### Smart TV
Интерфейс должен быть оптимизирован для управления пультом:
- стрелки;
- OK/Enter;
- Back;
- крупные карточки;
- горизонтальные списки;
- заметный focus state;
- минимум мелких элементов;
- полноэкранный видеоплеер.

Предусмотреть отдельный маршрут `/tv`.

---

## 3. Главный экран

Главная страница — Home Media Center.

Блоки:

### Продолжить просмотр
Фильмы, сериалы и видео с сохранением прогресса.

### Недавно добавлено
Последние добавленные:
- фильмы;
- музыка;
- книги;
- фотографии;
- игры;
- документы.

### Категории
- Фильмы
- Сериалы
- Мультфильмы
- Музыка
- Книги
- Аудиокниги
- Игры
- Фото
- Видео
- Файлы
- Документы
- Пароли

### Избранное
Любой объект можно добавить в Favorites.

---

## 4. Категории

### Media
- Movies
- TV Shows
- Cartoons
- Animated Series
- Documentaries
- Personal Videos

### Audio
- Music
- Audiobooks

### Reading
- Books
- Comics / Manga — предусмотреть архитектурно

### Games
- PC Games
- Console Games
- Emulators / ROMs — предусмотреть на будущее

### Personal
- Photos
- Personal Videos
- Albums

### Files
- Documents
- Archives
- Other Files

### Security
- Passwords
- Secure Notes
- Secret Files

---

## 5. Фильмы

Для фильма хранить:

- название;
- оригинальное название;
- описание;
- год;
- жанры;
- режиссёр;
- актёры;
- страна;
- возрастной рейтинг;
- постер;
- фон;
- трейлер;
- продолжительность;
- качество;
- разрешение;
- аудиодорожки;
- субтитры;
- размер файла;
- путь к файлу;
- пользовательскую оценку;
- избранное;
- дату добавления.

Видео должно открываться через собственный Web Video Player.

---

## 6. Сериалы

Структура:

```text
Сериал
 ├── Сезон 1
 │    ├── Серия 1
 │    ├── Серия 2
 │    └── ...
 ├── Сезон 2
 │    ├── Серия 1
 │    └── ...
```

Для сериала:
- название;
- описание;
- постер;
- backdrop;
- год;
- жанры;
- рейтинг;
- сезоны;
- количество серий.

Для серии:
- название;
- номер серии;
- номер сезона;
- описание;
- длительность;
- видеофайл;
- субтитры;
- аудиодорожки;
- прогресс просмотра.

После просмотра серии предлагать следующую.

---

## 7. Мультфильмы и мультсериалы

Использовать общую media-архитектуру.

Типы:
- Cartoon;
- Animated Series;
- Episode.

Не дублировать код.

---

## 8. Музыка

Структура:

```text
Исполнитель
    ↓
Альбом
    ↓
Треки
```

Для трека:
- название;
- исполнитель;
- альбом;
- жанр;
- год;
- номер трека;
- длительность;
- обложка;
- аудиофайл;
- bitrate;
- формат;
- размер;
- рейтинг;
- избранное.

Поддержать:
- MP3;
- FLAC;
- WAV;
- M4A;
- AAC;
- OGG.

### Music Player

Глобальный проигрыватель:
- Play;
- Pause;
- Next;
- Previous;
- Seek;
- Volume;
- Shuffle;
- Repeat;
- Queue;
- Playlist.

---

## 9. Книги

Поддержать:
- PDF;
- EPUB;
- FB2;
- MOBI;
- TXT.

Для книги:
- название;
- автор;
- описание;
- жанр;
- год;
- обложка;
- язык;
- ISBN;
- файл;
- размер;
- количество страниц;
- прогресс чтения;
- закладки;
- избранное.

---

## 10. Аудиокниги

Структура:

```text
Аудиокнига
 ├── Глава 1
 ├── Глава 2
 ├── Глава 3
 └── ...
```

Хранить:
- название;
- автор;
- чтец;
- описание;
- обложку;
- главы;
- аудиофайлы;
- длительность;
- прогресс;
- закладки.

---

## 11. Документальные фильмы

Documentary — отдельный MediaType, использующий общую архитектуру фильмов.

---

## 12. Фото

Полноценная система фотоальбомов:

```text
Фото
 ├── Семья
 ├── Дети
 ├── Отпуск 2026
 ├── День рождения
 └── Разное
```

Для фото:
- файл;
- preview;
- дата;
- EXIF;
- камера;
- разрешение;
- размер;
- GPS при наличии;
- альбом;
- теги;
- описание;
- избранное.

---

## 13. Видео

Личные видео:

```text
Мои видео
 ├── Семья
 ├── Дети
 ├── Отпуск
 └── Разное
```

Поддержать:
- MP4;
- MKV;
- MOV;
- AVI;
- WebM.

---

## 14. Галерея

Возможности:
- сетка фотографий;
- albums;
- fullscreen;
- slideshow;
- сортировка;
- поиск;
- фильтр по дате;
- избранное;
- теги.

Fullscreen viewer должен поддерживать:
- предыдущее;
- следующее;
- zoom;
- slideshow.

---

## 15. Файлы

Универсальный File Manager.

Структура:

```text
Файлы
 ├── Работа
 ├── Архив
 ├── Программы
 ├── ISO
 ├── ZIP
 └── Другое
```

Возможности:
- папки;
- загрузка;
- скачивание;
- переименование;
- удаление;
- перемещение;
- копирование;
- поиск;
- сортировка;
- предпросмотр;
- размер;
- дата изменения.

---

## 16. Документы

Поддержать:
- PDF;
- DOC;
- DOCX;
- XLS;
- XLSX;
- PPT;
- PPTX;
- TXT;
- CSV;
- изображения документов.

Preview — где возможно.

---

## 17. Password Manager

Создать отдельный защищённый Password Manager.

Пароли нельзя хранить в открытом виде.

Использовать:
- encryption at rest;
- master password;
- безопасное хранение ключей;
- отдельную модель SecureItem.

Структура:

```text
Пароли
 ├── Google
 ├── Facebook
 ├── Банки
 ├── Hosting
 ├── Servers
 └── Другое
```

Для записи:
- название;
- login;
- password;
- URL;
- заметка;
- категория;
- username;
- дополнительные поля.

Пароль по умолчанию скрыт.

Кнопки:
- показать;
- копировать.

После копирования пароль должен автоматически удаляться из clipboard через заданное время.

ВАЖНО:
- не использовать plaintext-хранение;
- использовать современное шифрование;
- продумать модель угроз до реализации;
- master password не должен храниться в БД в открытом виде.

---

## 18. Глобальный поиск

Поиск по всей библиотеке.

Например:

```text
Harry Potter
```

Результаты:
- фильмы;
- сериалы;
- книги;
- музыка;
- файлы;
- документы.

Поиск по:
- названию;
- оригинальному названию;
- автору;
- исполнителю;
- актёрам;
- тегам;
- описанию;
- имени файла.

На TV предусмотреть удобный ввод.

---

## 19. История

Хранить историю:
- просмотренных фильмов;
- сериалов;
- прослушанной музыки;
- аудиокниг;
- прочитанных книг;
- открытых файлов.

---

## 20. Continue Watching / Progress

Прогресс хранить на сервере.

Пример:

```text
Breaking Bad
S03E05
32:15 / 47:20

████████████░░░
```

При открытии контента продолжать с последней позиции.

---

## 21. Автоматическое сканирование

Пользователь указывает директории:

```text
/media/movies
/media/series
/media/music
/media/books
/media/photos
```

Система периодически сканирует директории.

При обнаружении нового файла:

1. определить тип;
2. определить формат;
3. извлечь metadata;
4. найти существующий объект;
5. создать или обновить MediaItem;
6. создать thumbnail;
7. добавить в библиотеку.

Пример:

```text
/movies/Interstellar (2014).mkv
```

автоматически создаёт фильм:

```text
Interstellar
2014
```

---

## 22. Metadata

Предусмотреть интеграцию внешних API для:
- названий;
- описаний;
- постеров;
- backdrop;
- актёров;
- режиссёров;
- жанров;
- рейтингов.

Внешний API не должен быть обязательным.

При отсутствии интернета локальная библиотека должна продолжать работать.

Metadata необходимо кешировать локально.

---

## 23. Video Player

Функции:
- Play/Pause;
- seek;
- volume;
- fullscreen;
- playback speed;
- subtitles;
- audio tracks;
- quality;
- next episode;
- previous episode;
- skip;
- picture-in-picture где поддерживается.

Для сериалов:

```text
S01E05

< Previous
Pause
Next >
```

---

## 24. TV Mode

Создать отдельный TV UI:

```text
/tv
```

Требования:
- крупные элементы;
- управление стрелками;
- OK/Enter;
- Back;
- focus state;
- горизонтальные carousel;
- минимум текста;
- тёмный интерфейс;
- fullscreen player;
- autoplay next episode;
- удобная навигация с пульта.

TV Mode не должен быть просто увеличенной мобильной версией.

---

## 25. Игры

Для игры хранить:
- название;
- платформа;
- год;
- жанр;
- обложка;
- описание;
- executable/file;
- размер;
- версия;
- путь;
- пользовательскую оценку.

На первом этапе — каталог игр.

В будущем предусмотреть:
- локальный запуск;
- emulator;
- streaming.

---

## 26. Архитектура

Не создавать полностью независимые системы для каждого типа контента.

Использовать общую архитектуру.

Например:

```text
MediaItem
 ├── Movie
 ├── Series
 ├── Episode
 ├── Cartoon
 ├── Documentary
 ├── Music
 ├── Book
 ├── Audiobook
 ├── Game
 ├── Photo
 ├── Video
 └── File
```

Общие свойства:

```text
id
type
title
description
file
thumbnail
size
created_at
updated_at
favorite
metadata
```

Специфические свойства — в специализированных сущностях.

---

## 27. Storage

Файлы и БД разделены.

Пример:

```text
/storage/media/
    movies/
    series/
    music/
    books/
    audiobooks/
    games/
    photos/
    videos/
    documents/
    files/
```

Большие файлы не хранить в БД.

БД хранит metadata и ссылки на файлы.

---

## 28. Thumbnails

Для видео и фотографий автоматически создавать thumbnails.

Видео:
- poster;
- preview;
- несколько размеров.

Фото:
- thumbnail;
- medium;
- original.

При списках использовать thumbnails, а не оригинальные файлы.

---

## 29. Users

На первом этапе:
- один основной пользователь.

Архитектура должна позволять добавить несколько пользователей.

```text
User
 ├── Personal Library
 ├── Favorites
 ├── Watch Progress
 ├── Playlists
 └── Secure Items
```

---

## 30. Авторизация

Поддержать:
- login;
- password;
- logout;
- session/token;
- remember me.

Архитектурно предусмотреть 2FA.

Для внешнего доступа обязательна усиленная безопасность.

---

## 31. Admin Panel

Admin должен иметь возможность:
- добавлять директории;
- запускать сканирование;
- смотреть состояние сканирования;
- управлять пользователями;
- управлять категориями;
- удалять metadata;
- редактировать фильмы;
- редактировать сериалы;
- управлять storage;
- смотреть размер хранилища;
- видеть ошибки scanner;
- управлять настройками.

---

## 32. Dashboard

Показывать статистику:

```text
Library

Movies             324
Series              42
Episodes           681
Music             4521
Books              312
Audiobooks          86
Photos           28421
Videos              642
Games               87
Files             2341

Storage

Used:
4.8 TB / 8 TB

Free:
3.2 TB
```

---

## 33. Playlists

Предусмотреть:
- Моя музыка;
- Для машины;
- Любимые фильмы;
- Детские мультфильмы;
- Фильмы на выходные.

Playlist может содержать элементы соответствующего типа.

---

## 34. Tags

Пользовательские теги:

```text
#семья
#дети
#любимое
#посмотреть
#работа
#архив
```

Один объект может иметь несколько тегов.

---

## 35. Smart Collections

В будущем:

- Фильмы 2025;
- Лучшие фильмы;
- Непросмотренные;
- Детские;
- 4K;
- Избранное;
- Недавно добавленные.

---

## 36. Backup

Предусмотреть backup:

1. Database;
2. metadata;
3. configuration;
4. encrypted password vault.

Большие media-файлы можно не включать в DB backup.

---

## 37. API

Систему строить API-first.

Web UI, TV UI и будущее мобильное приложение используют единый API.

Пример:

```text
/api/v1/auth
/api/v1/movies
/api/v1/series
/api/v1/music
/api/v1/books
/api/v1/photos
/api/v1/files
/api/v1/search
/api/v1/favorites
/api/v1/history
/api/v1/playlists
/api/v1/progress
```

---

## 38. Технологический стек

### Backend
- PHP 8.3+
- Laravel 11/12+
- PostgreSQL
- Redis
- Laravel Queue

### Frontend
- Blade
- HTML
- CSS
- JavaScript

Не использовать React/Next.js без необходимости.

Цель — простой, быстрый и надёжный frontend.

---

## 39. Background Jobs

Через Queue выполнять:
- сканирование директорий;
- генерацию thumbnails;
- извлечение metadata;
- анализ видео;
- импорт музыки;
- создание previews;
- backup.

Не выполнять тяжёлые операции во время HTTP request.

---

## 40. Scanner

Создать Scanner Service:

```text
LibraryScanner
    ↓
DetectFileType
    ↓
ExtractMetadata
    ↓
FindExistingItem
    ↓
Create/Update MediaItem
    ↓
GenerateThumbnail
```

Повторное сканирование не должно создавать дубликаты.

Использовать:
- file hash;
- path;
- размер;
- modification date.

---

## 41. Дубликаты

Обнаруживать одинаковые файлы.

Пример:

```text
Interstellar.mkv
Interstellar (1).mkv
Interstellar copy.mkv
```

В админке:

```text
Найдено 3 потенциальных дубликата

[Compare]
[Keep]
[Delete]
```

---

## 42. Производительность

Система должна быть рассчитана минимум на:
- 100 000+ фотографий;
- 10 000+ видео;
- 10 000+ аудиофайлов;
- тысячи документов.

Использовать:
- pagination;
- lazy loading;
- cursor pagination где необходимо;
- thumbnails;
- caching;
- database indexes;
- background jobs.

Не загружать всю библиотеку в память.

---

## 43. UI

Основной стиль:
- Dark Mode;
- современный;
- минималистичный;
- по концепции близкий к Plex / Jellyfin / Netflix;
- не копировать дизайн.

Навигация:

```text
HOME

MEDIA
  Movies
  Series
  Cartoons
  Documentaries

AUDIO
  Music
  Audiobooks

READ
  Books

PERSONAL
  Photos
  Videos

FILES
  Documents
  Files

GAMES
  Games

SECURITY
  Passwords

OTHER
  Favorites
  History
  Playlists
```

На TV — отдельная оптимизированная навигация.

---

## 44. Responsive

Один backend/API.

UI:
- Desktop;
- Tablet;
- Mobile;
- TV.

TV может использовать отдельный layout.

---

## 45. Безопасность

Обязательно учитывать:
- CSRF;
- XSS;
- SQL Injection;
- authorization;
- rate limiting;
- secure sessions;
- password hashing;
- encrypted secrets;
- secure file access.

Нельзя получить приватный файл простым изменением URL.

Все private files должны отдаваться через контролируемый access layer.

---

## 46. Private Storage

Личные файлы хранить вне public directory:

```text
storage/app/private/
```

Публичными делать только действительно необходимые ресурсы.

---

## 47. Settings

### General
- имя библиотеки;
- язык;
- часовой пояс;
- тема.

### Storage
- directories;
- scan interval;
- storage limits.

### Media
- metadata providers;
- thumbnail settings.

### Security
- password settings;
- sessions;
- 2FA;
- encryption.

### Playback
- default quality;
- subtitles;
- audio track;
- autoplay next episode.

---

# 48. Этапы разработки

## Phase 1 — Core
- Laravel project;
- authentication;
- users;
- database;
- storage;
- MediaItem architecture;
- categories;
- file manager;
- scanner;
- basic frontend;
- responsive UI.

## Phase 2 — Video
- Movies;
- Series;
- Episodes;
- Cartoons;
- Documentaries;
- Video Player;
- watch progress;
- Continue Watching.

## Phase 3 — Audio
- Music;
- Albums;
- Artists;
- Audio Player;
- Playlists;
- Audiobooks.

## Phase 4 — Books
- Books;
- PDF;
- EPUB;
- reader;
- progress;
- bookmarks.

## Phase 5 — Photos
- Gallery;
- Albums;
- thumbnails;
- EXIF;
- fullscreen viewer.

## Phase 6 — Files
- Documents;
- File Manager;
- previews;
- uploads;
- downloads.

## Phase 7 — Security
- Password Manager;
- encrypted storage;
- Secure Notes.

## Phase 8 — TV
- TV Mode;
- remote navigation;
- optimized player;
- fullscreen interface.

## Phase 9 — Advanced
- metadata APIs;
- duplicate detection;
- smart collections;
- advanced search;
- backup;
- multi-user;
- external access.

---

# 49. Инструкция для Cursor

Перед написанием кода НЕ начинать сразу создавать контроллеры и страницы.

Сначала:

1. Проанализировать ТЗ.
2. Предложить архитектуру проекта.
3. Предложить структуру БД.
4. Предложить Laravel Models.
5. Предложить связи моделей.
6. Предложить migrations.
7. Предложить API endpoints.
8. Предложить структуру Storage.
9. Предложить систему Scanner.
10. Предложить MediaItem architecture.
11. Отдельно спроектировать Password Manager.
12. Отдельно спроектировать TV Mode.
13. Определить MVP.
14. Определить порядок реализации.
15. Указать потенциальные архитектурные проблемы и предложить решения.

После этого начать реализацию поэтапно.

---

# 50. Основной принцип

Система должна восприниматься не как обычный файловый менеджер.

Это:

> Personal Home Media Center + Digital Library + Private Cloud + Password Vault.

Главная цель:

> Один домашний сервер хранит всю цифровую библиотеку пользователя, а доступ к ней осуществляется с компьютера, телефона, планшета и телевизора.
