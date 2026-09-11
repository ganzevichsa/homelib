<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartoonFileRequest;
use App\Models\Cartoon;
use App\Models\CartoonFile;
use App\Support\CartoonLibrary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartoonFileController extends Controller
{
    public function create(Cartoon $cartoon, CartoonLibrary $library): View
    {
        return view('admin.cartoons.files.create', [
            'cartoon' => $cartoon,
            'directory' => $library->disk()->path($library->directory()),
        ]);
    }

    public function search(Request $request, CartoonLibrary $library): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'filenames' => $library->suggest($query),
        ]);
    }

    public function store(StoreCartoonFileRequest $request, Cartoon $cartoon, CartoonLibrary $library): RedirectResponse
    {
        $cartoon->files()->create([
            ...$library->attributes($request->validated('filename')),
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'year' => $request->validated('year'),
            'sort_order' => (int) $cartoon->files()->max('sort_order') + 1,
        ]);

        return redirect()
            ->route('admin.cartoons.edit', $cartoon)
            ->with('status', 'cartoon-file-created');
    }

    public function destroy(Cartoon $cartoon, CartoonFile $file): RedirectResponse
    {
        abort_unless($file->cartoon_id === $cartoon->id, 404);

        $file->delete();

        return redirect()
            ->route('admin.cartoons.edit', $cartoon)
            ->with('status', 'cartoon-file-deleted');
    }
}
