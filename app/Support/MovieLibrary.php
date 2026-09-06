<?php

namespace App\Support;

use App\Models\MovieFile;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieLibrary
{
    public function disk(): Filesystem
    {
        return Storage::disk((string) config('media.disk'));
    }

    public function directory(): string
    {
        return (string) config('media.movies.directory');
    }

    /**
     * @return list<string>
     */
    public function files(): array
    {
        $this->disk()->makeDirectory($this->directory());

        $extensions = config('media.movies.extensions');

        return collect($this->disk()->files($this->directory()))
            ->map(fn (string $path): string => basename($path))
            ->filter(fn (string $filename): bool => Str::endsWith(
                Str::lower($filename),
                collect($extensions)->map(fn (string $extension): string => '.'.$extension)->all(),
            ))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public function unusedFiles(): array
    {
        $used = MovieFile::query()->pluck('filename');

        return array_values(array_filter(
            $this->files(),
            fn (string $filename): bool => ! $used->contains($filename),
        ));
    }

    public function relativePath(string $filename): string
    {
        return $this->directory().'/'.$filename;
    }

    /**
     * @return array{filename: string, path: string, extension: string, mime_type: ?string, size: int, hash: string}
     */
    public function attributes(string $filename): array
    {
        $relativePath = $this->relativePath($filename);
        $fullPath = $this->disk()->path($relativePath);

        return [
            'filename' => $filename,
            'path' => $relativePath,
            'extension' => strtolower((string) pathinfo($filename, PATHINFO_EXTENSION)),
            'mime_type' => is_file($fullPath) ? (mime_content_type($fullPath) ?: null) : null,
            'size' => $this->disk()->size($relativePath),
            'hash' => is_file($fullPath) ? (hash_file('sha256', $fullPath) ?: '') : '',
        ];
    }
}
