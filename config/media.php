<?php

return [
    'disk' => 'media',

    'movies' => [
        'directory' => 'movies',
        'extensions' => ['mkv', 'mp4', 'avi', 'mov', 'webm', 'm4v'],
    ],

    'series' => [
        'directory' => 'series',
        'extensions' => ['mkv', 'mp4', 'avi', 'mov', 'webm', 'm4v'],
    ],

    'cartoons' => [
        'directory' => 'cartoons',
        'extensions' => ['mkv', 'mp4', 'avi', 'mov', 'webm', 'm4v'],
    ],

    'animated_series' => [
        'directory' => 'animated-series',
        'extensions' => ['mkv', 'mp4', 'avi', 'mov', 'webm', 'm4v'],
    ],

    'music' => [
        'directory' => 'music',
        'extensions' => ['mp3', 'flac', 'wav', 'm4a', 'aac', 'ogg', 'opus'],
    ],

    'books' => [
        'directory' => 'books',
        'extensions' => ['pdf', 'epub', 'fb2', 'mobi', 'txt'],
    ],

    'audiobooks' => [
        'directory' => 'audiobooks',
        'extensions' => ['mp3', 'flac', 'wav', 'm4a', 'aac', 'ogg', 'opus'],
    ],

    'files' => [
        'directory' => 'files',
        'extensions' => ['pdf', 'txt', 'zip', 'rar', '7z', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'rtf', 'odt', 'ods', 'odp'],
    ],

    'games' => [
        'directory' => 'games',
        'extensions' => ['iso', 'img', 'exe', 'msi', 'zip', 'rar', '7z'],
    ],

    'gallery' => [
        'directory' => 'gallery',
        'extensions' => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'webm', 'mov', 'm4v', 'mkv', 'avi'],
    ],

    'posters' => [
        'directory' => 'posters',
        'mimes' => ['jpg', 'jpeg', 'png', 'webp'],
        'max_kilobytes' => 5120,
    ],
];
