<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'original_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1870', 'max:2100'],
            'genre_ids' => ['nullable', 'array'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
            'country_ids' => ['nullable', 'array'],
            'country_ids.*' => ['integer', 'exists:countries,id'],
            'poster' => [
                'nullable',
                'image',
                'max:'.(int) config('media.posters.max_kilobytes'),
                'mimes:'.implode(',', config('media.posters.mimes')),
            ],
            'remove_poster' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function movieAttributes(): array
    {
        $description = $this->validated('description');

        return [
            'title' => [
                Locale::Ru->value => $this->validated('title'),
            ],
            'original_title' => $this->validated('original_title'),
            'description' => $description ? [
                Locale::Ru->value => $description,
            ] : null,
            'year' => $this->validated('year'),
        ];
    }

    /**
     * @return list<int>
     */
    public function genreIds(): array
    {
        return array_map('intval', $this->validated('genre_ids') ?? []);
    }

    /**
     * @return list<int>
     */
    public function countryIds(): array
    {
        return array_map('intval', $this->validated('country_ids') ?? []);
    }
}
