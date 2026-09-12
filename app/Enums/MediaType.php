<?php

namespace App\Enums;

enum MediaType: string
{
    case Movie = 'movie';
    case Series = 'series';
    case Cartoon = 'cartoon';
    case AnimatedSeries = 'animated_series';
    case Documentary = 'documentary';
    case Music = 'music';
    case Book = 'book';
    case Audiobook = 'audiobook';
    case Game = 'game';
    case Gallery = 'gallery';
    case Document = 'document';
    case File = 'file';
    case Other = 'other';

    public function directory(): string
    {
        return match ($this) {
            self::Movie => 'movies',
            self::Series => 'series',
            self::Cartoon => 'cartoons',
            self::AnimatedSeries => 'animated-series',
            self::Documentary => 'documentaries',
            self::Music => 'music',
            self::Book => 'books',
            self::Audiobook => 'audiobooks',
            self::Game => 'games',
            self::Gallery => 'gallery',
            self::Document => 'documents',
            self::File => 'files',
            self::Other => 'other',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Movie => 'Фильмы',
            self::Series => 'Сериалы',
            self::Cartoon => 'Мультфильмы',
            self::AnimatedSeries => 'Мультсериалы',
            self::Documentary => 'Документальные',
            self::Music => 'Музыка',
            self::Book => 'Книги',
            self::Audiobook => 'Аудиокниги',
            self::Game => 'Игры',
            self::Gallery => 'Галерея',
            self::Document => 'Документы',
            self::File => 'Файлы',
            self::Other => 'Остальное',
        };
    }

    /**
     * @return list<self>
     */
    public static function home(): array
    {
        return [
            self::Movie,
            self::Series,
            self::Cartoon,
            self::AnimatedSeries,
            self::Music,
            self::Book,
            self::File,
            self::Other,
            self::Audiobook,
            self::Game,
            self::Gallery,
            self::Document,
        ];
    }

    public function homeSize(): string
    {
        return match ($this) {
            self::Movie, self::Series, self::Cartoon => 'text-2xl sm:text-4xl md:text-6xl',
            self::AnimatedSeries, self::Music, self::Book, self::Audiobook => 'text-xl sm:text-3xl md:text-4xl',
            default => 'text-lg sm:text-2xl md:text-3xl',
        };
    }
}
