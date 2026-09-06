<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Россия',
            'США',
            'Великобритания',
            'Франция',
            'Германия',
            'Италия',
            'Испания',
            'Канада',
            'Австралия',
            'Япония',
            'Южная Корея',
            'Китай',
            'Индия',
            'Польша',
            'Швеция',
            'Норвегия',
            'Дания',
            'Нидерланды',
            'Бельгия',
            'Украина',
            'Беларусь',
            'Казахстан',
            'Чехия',
            'Венгрия',
            'Бразилия',
            'Мексика',
            'Аргентина',
            'Турция',
            'Гонконг',
            'Новая Зеландия',
            'Ирландия',
            'Швейцария',
            'Австрия',
            'Финляндия',
        ] as $name) {
            Country::query()->firstOrCreate(['name' => $name]);
        }
    }
}
