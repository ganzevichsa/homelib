<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFileEntryRequest;
use App\Models\FileEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FileEntryController extends Controller
{
    public function index(): View
    {
        return view('admin.files.index', [
            'entries' => FileEntry::query()->with('files')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.files.create');
    }

    public function store(StoreFileEntryRequest $request): RedirectResponse
    {
        $entry = FileEntry::query()->create($request->attributesForModel());

        return redirect()->route('admin.files.edit', $entry)->with('status', 'created');
    }

    public function edit(FileEntry $fileEntry): View
    {
        $fileEntry->load('files');

        return view('admin.files.edit', ['entry' => $fileEntry]);
    }

    public function update(StoreFileEntryRequest $request, FileEntry $fileEntry): RedirectResponse
    {
        $fileEntry->update($request->attributesForModel());

        return redirect()->route('admin.files.edit', $fileEntry)->with('status', 'updated');
    }

    public function destroy(FileEntry $fileEntry): RedirectResponse
    {
        $fileEntry->delete();

        return redirect()->route('admin.files')->with('status', 'deleted');
    }
}
