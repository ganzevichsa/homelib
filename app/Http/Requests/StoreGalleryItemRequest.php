<?php

namespace App\Http\Requests;

use App\Models\GalleryItem;
use App\Support\DiskLibrary;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryItemRequest extends FormRequest
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
        $library = new DiskLibrary('gallery', GalleryItem::class);

        return [
            'title' => ['nullable', 'string', 'max:255'],
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
