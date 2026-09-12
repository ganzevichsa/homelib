<?php

namespace App\Support;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DiskLibrary
{
    /**
     * @param  class-string<Model>  $usedModel
     */
    public function __construct(
        private readonly string $section,
        private readonly string $usedModel,
    ) {}

    public function disk(): Filesystem
    {
        return Storage::disk((string) config('media.disk'));
    }

    public function directory(): string
    {
        return (string) config("media.{$this->section}.directory");
    }

    /**
     * @return list<string>
     */
    public function files(): array
    {
        $this->disk()->makeDirectory($this->directory());

        return collect($this->disk()->files($this->directory()))
            ->map(fn (string $path): string => basename($path))
            ->filter(fn (string $filename): bool => $this->hasAllowedExtension($filename))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public function unattachedFilenames(): array
    {
        $used = $this->usedModel::query()->pluck('filename');

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

        return ! $this->usedModel::query()->where('filename', $filename)->exists();
    }

    public function relativePath(string $filename): string
    {
        return $this->directory().'/'.$filename;
    }

    /**
     * @return array{filename: string, path: string, extension: string, mime_type: ?string, size: int}
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
        ];
    }

    public function hasAllowedExtension(string $filename): bool
    {
        $extensions = collect(config("media.{$this->section}.extensions"))
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
