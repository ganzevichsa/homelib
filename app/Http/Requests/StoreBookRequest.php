<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'author' => ['required', 'string', 'max:255'],
            'original_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1000', 'max:2100'],
            'isbn' => ['nullable', 'string', 'max:32'],
            'genre_ids' => ['nullable', 'array'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
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
    public function bookAttributes(): array
    {
        $description = $this->validated('description');

        return [
            'title' => [
                Locale::Ru->value => $this->validated('title'),
            ],
            'author' => $this->validated('author'),
            'original_title' => $this->validated('original_title'),
            'description' => $description ? [
                Locale::Ru->value => $description,
            ] : null,
            'year' => $this->validated('year'),
            'isbn' => $this->validated('isbn'),
        ];
    }

    /**
     * @return list<int>
     */
    public function genreIds(): array
    {
        return array_map('intval', $this->validated('genre_ids') ?? []);
    }
}
