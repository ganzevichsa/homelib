<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Боевик',
            'Комедия',
            'Драма',
            'Триллер',
            'Ужасы',
            'Фантастика',
            'Фэнтези',
            'Детектив',
            'Приключения',
            'Мелодрама',
            'Криминал',
            'Военный',
            'Исторический',
            'Документальный',
            'Семейный',
            'Спорт',
            'Музыка',
            'Вестерн',
            'Биография',
            'Рок',
            'Поп',
            'Джаз',
            'Электроника',
            'Хип-хоп',
            'Классика',
            'Метал',
            'Блюз',
            'Поэзия',
            'Проза',
        ] as $name) {
            Genre::query()->firstOrCreate(['name' => $name]);
        }
    }
}
