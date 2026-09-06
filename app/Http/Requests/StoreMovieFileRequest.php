<?php

namespace App\Http\Requests;

use App\Support\MovieLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieFileRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1870', 'max:2100'],
            'description' => ['nullable', 'string'],
            'filename' => ['required', 'string', Rule::in($library->unattachedFilenames())],
        ];
    }
}
