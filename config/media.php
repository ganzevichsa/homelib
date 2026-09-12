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

    'posters' => [
        'directory' => 'posters',
        'mimes' => ['jpg', 'jpeg', 'png', 'webp'],
        'max_kilobytes' => 5120,
    ],
];
