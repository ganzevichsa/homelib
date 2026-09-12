<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1970', 'max:2100'],
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
    public function attributesForModel(): array
    {
        $description = $this->validated('description');

        return [
            'title' => [Locale::Ru->value => $this->validated('title')],
            'description' => $description ? [Locale::Ru->value => $description] : null,
            'year' => $this->validated('year'),
        ];
    }
}
