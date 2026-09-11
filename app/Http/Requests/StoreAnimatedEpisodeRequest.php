<?php

namespace App\Http\Requests;

use App\Support\AnimatedSeriesLibrary;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnimatedEpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $library = app(AnimatedSeriesLibrary::class);
        $season = $this->route('animatedSeason');

        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                'max:500',
                Rule::unique('animated_episodes', 'number')->where('animated_season_id', $season?->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'filename' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($library): void {
                    if (! is_string($value) || ! $library->isAvailable($value)) {
                        $fail('Такого файла нет в папке или он уже привязан.');
                    }
                },
            ],
        ];
    }
}
