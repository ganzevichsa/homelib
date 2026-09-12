<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachedFileRequest;
use App\Models\FileEntry;
use App\Models\FileEntryFile;
use App\Support\DiskLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FileEntryFileController extends Controller
{
    public function create(FileEntry $fileEntry): View
    {
        $library = new DiskLibrary('files', FileEntryFile::class);

        return view('admin.files.attachments.create', [
            'entry' => $fileEntry,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $library = new DiskLibrary('files', FileEntryFile::class);

        return response()->json([
            'filenames' => $library->suggest(trim((string) $request->query('q', ''))),
        ]);
    }

    public function store(StoreAttachedFileRequest $request, FileEntry $fileEntry): RedirectResponse
    {
        $library = new DiskLibrary('files', FileEntryFile::class);

        $fileEntry->files()->create([
            ...$library->attributes($request->validated('filename')),
            'title' => $request->validated('title'),
            'sort_order' => (int) $fileEntry->files()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.files.edit', $fileEntry)->with('status', 'file-created');
    }

    public function destroy(FileEntry $fileEntry, FileEntryFile $file): RedirectResponse
    {
        abort_unless($file->file_entry_id === $fileEntry->id, 404);
        $file->delete();

        return redirect()->route('admin.files.edit', $fileEntry)->with('status', 'file-deleted');
    }
}
