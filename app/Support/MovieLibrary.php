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
            ->filter(fn (string $filename): bool => $this->hasAllowedExtension($filename))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Filenames in the movies folder that are not yet attached to a movie.
     *
     * @return list<string>
     */
    public function unattachedFilenames(): array
    {
        $used = MovieFile::query()->pluck('filename');

        return array_values(array_filter(
            $this->files(),
            fn (string $filename): bool => ! $used->contains($filename),
        ));
    }

    /**
     * @return list<string>
     */
    public function suggest(string $query, int $limit = 15): array
    {
        $query = Str::lower(trim($query));

        if (Str::length($query) < 2) {
            return [];
        }

        return collect($this->unattachedFilenames())
            ->filter(fn (string $filename): bool => Str::contains(Str::lower($filename), $query))
            ->take($limit)
            ->values()
            ->all();
    }

    public function isAvailable(string $filename): bool
    {
        if (! $this->isSafeFilename($filename) || ! $this->hasAllowedExtension($filename)) {
            return false;
        }

        if (! $this->disk()->exists($this->relativePath($filename))) {
            return false;
        }

        return ! MovieFile::query()->where('filename', $filename)->exists();
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

    public function hasAllowedExtension(string $filename): bool
    {
        $extensions = collect(config('media.movies.extensions'))
            ->map(fn (string $extension): string => '.'.$extension)
            ->all();

        return Str::endsWith(Str::lower($filename), $extensions);
    }

    public function isSafeFilename(string $filename): bool
    {
        return $filename !== ''
            && $filename === basename($filename)
            && ! str_contains($filename, '..');
    }
}
