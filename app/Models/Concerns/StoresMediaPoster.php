<?php

namespace App\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait StoresMediaPoster
{
    abstract protected function posterPrefix(): string;

    public function hasPoster(): bool
    {
        return filled($this->poster)
            && Storage::disk((string) config('media.disk'))->exists($this->poster);
    }

    public function storePoster(UploadedFile $file): void
    {
        $this->deletePosterFile();

        $filename = $this->posterPrefix().'-'.$this->id.'.'.strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs((string) config('media.posters.directory'), $filename, (string) config('media.disk'));

        $this->update(['poster' => $path]);
    }

    public function deletePosterFile(): void
    {
        if (filled($this->poster)) {
            Storage::disk((string) config('media.disk'))->delete($this->poster);
        }
    }
}
