<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use App\Support\MovieLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $library = app(MovieLibrary::class);

        return [
            'title_ru' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'original_title' => ['nullable', 'string', 'max:255'],
            'description_ru' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1870', 'max:2100'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['string', Rule::in($library->unusedFiles())],
            'file_titles' => ['nullable', 'array'],
            'file_titles.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function movieAttributes(): array
    {
        $titleEn = $this->validated('title_en') ?: $this->validated('title_ru');
        $descriptionRu = $this->validated('description_ru');
        $descriptionEn = $this->validated('description_en') ?: $descriptionRu;

        return [
            'title' => [
                Locale::Ru->value => $this->validated('title_ru'),
                Locale::En->value => $titleEn,
            ],
            'original_title' => $this->validated('original_title'),
            'description' => $descriptionRu || $descriptionEn ? [
                Locale::Ru->value => $descriptionRu,
                Locale::En->value => $descriptionEn,
            ] : null,
            'year' => $this->validated('year'),
        ];
    }

    /**
     * @return list<array{filename: string, title: ?string}>
     */
    public function selectedFiles(): array
    {
        $titles = $this->validated('file_titles') ?? [];

        return collect($this->validated('files'))
            ->values()
            ->map(fn (string $filename, int $index): array => [
                'filename' => $filename,
                'title' => $titles[$filename] ?? null,
                'sort_order' => $index,
            ])
            ->all();
    }
}
