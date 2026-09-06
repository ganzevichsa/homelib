# Конвертация MKV/AVI → MP4 для сайта

Папка:

`C:\laragon\www\homelib\storage\app\private\media\movies`

Для Homelib нужна эта команда: видео копируется, звук в AAC стерео. Иначе в браузере часто нет аудио.

---

## Один файл

Подставь своё имя вместо `фильм`.

```powershell
ffmpeg -i "C:\laragon\www\homelib\storage\app\private\media\movies\2.mkv" -c:v copy -c:a aac -ac 2 -b:a 192k -movflags +faststart -y "C:\laragon\www\homelib\storage\app\private\media\movies\2.mp4"
ffmpeg -i "C:\laragon\www\homelib\storage\app\private\media\movies\2.mkv" -c:v copy -c:a aac -ac 2 -b:a 192k -movflags +faststart -y "C:\laragon\www\homelib\storage\app\private\media\movies\2.mp4"

```

Пример:

```powershell
ffmpeg -i "C:\laragon\www\homelib\storage\app\private\media\movies\2.mkv" -c:v copy -c:a aac -ac 2 -b:a 192k -movflags +faststart -y "C:\laragon\www\homelib\storage\app\private\media\movies\2.mp4"
```

---

## Вся папка

```powershell
Get-ChildItem "C:\laragon\www\homelib\storage\app\private\media\movies\*" -Include *.mkv,*.avi | ForEach-Object { ffmpeg -i $_.FullName -c:v copy -c:a aac -ac 2 -b:a 192k -movflags +faststart -y ($_.DirectoryName + "\" + $_.BaseName + ".mp4") }
```

---

## Если ffmpeg ругается на видео (часто старые AVI)

```powershell
ffmpeg -i "C:\laragon\www\homelib\storage\app\private\media\movies\фильм.avi" -c:v libx264 -crf 18 -preset medium -c:a aac -ac 2 -b:a 192k -movflags +faststart -y "C:\laragon\www\homelib\storage\app\private\media\movies\фильм.mp4"
```

---

Исходные `.mkv` / `.avi` не удаляются. В карточке фильма привяжи `.mp4`.
