<?php

namespace App\Http\Requests;

use App\Models\FileEntryFile;
use App\Models\GalleryItem;
use App\Models\GameFile;
use App\Support\DiskLibrary;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttachedFileRequest extends FormRequest
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
        $library = $this->library();

        return [
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

    public function library(): DiskLibrary
    {
        return match (true) {
            $this->route('fileEntry') !== null => new DiskLibrary('files', FileEntryFile::class),
            $this->route('game') !== null => new DiskLibrary('games', GameFile::class),
            default => new DiskLibrary('gallery', GalleryItem::class),
        };
    }
}
