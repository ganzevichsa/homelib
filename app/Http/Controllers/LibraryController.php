<?php

namespace App\Http\Controllers;

use App\Enums\MediaType;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function show(MediaType $type): View
    {
        return view('library.show', [
            'type' => $type,
        ]);
    }
}
