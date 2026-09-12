<?php

namespace App\Http\Controllers;

use App\Models\FileEntry;
use App\Models\FileEntryFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LibraryFileMediaController extends Controller
{
    public function stream(FileEntry $fileEntry, FileEntryFile $file): BinaryFileResponse
    {
        abort_unless($file->file_entry_id === $fileEntry->id, 404);

        $disk = Storage::disk((string) config('media.disk'));
        abort_unless($disk->exists($file->path), 404);

        $disposition = $file->isBrowserReadable() ? 'inline' : 'attachment';

        return response()->file($disk->path($file->path), [
            'Content-Type' => $file->browserMime(),
            'Content-Disposition' => $disposition.'; filename="'.$file->filename.'"',
            'Accept-Ranges' => 'bytes',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
