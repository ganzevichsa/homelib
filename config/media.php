<?php

return [
    'disk' => 'media',

    'movies' => [
        'directory' => 'movies',
        'extensions' => ['mkv', 'mp4', 'avi', 'mov', 'webm', 'm4v'],
    ],

    'posters' => [
        'directory' => 'posters',
        'mimes' => ['jpg', 'jpeg', 'png', 'webp'],
        'max_kilobytes' => 5120,
    ],
];
