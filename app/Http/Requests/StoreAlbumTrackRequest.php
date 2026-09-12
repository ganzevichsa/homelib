<?php

namespace App\Http\Requests;

use App\Support\MusicLibrary;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAlbumTrackRequest extends FormRequest
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
        $library = app(MusicLibrary::class);
        $album = $this->route('album');

        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                'max:500',
                Rule::unique('album_tracks', 'number')->where('album_id', $album?->id),
            ],
            'title' => ['required', 'string', 'max:255'],
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
