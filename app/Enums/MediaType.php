<?php

namespace App\Enums;

enum MediaType: string
{
    case Movie = 'movie';
    case Series = 'series';
    case Episode = 'episode';
    case Cartoon = 'cartoon';
    case AnimatedSeries = 'animated_series';
    case Documentary = 'documentary';
    case Music = 'music';
    case Book = 'book';
    case Audiobook = 'audiobook';
    case Game = 'game';
    case Photo = 'photo';
    case Video = 'video';
    case Document = 'document';
    case File = 'file';

    public function directory(): string
    {
        return match ($this) {
            self::Movie => 'movies',
            self::Series, self::Episode => 'series',
            self::Cartoon => 'cartoons',
            self::AnimatedSeries => 'animated-series',
            self::Documentary => 'documentaries',
            self::Music => 'music',
            self::Book => 'books',
            self::Audiobook => 'audiobooks',
            self::Game => 'games',
            self::Photo => 'photos',
            self::Video => 'videos',
            self::Document => 'documents',
            self::File => 'files',
        };
    }
}
