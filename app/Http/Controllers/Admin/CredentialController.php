<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCredentialRequest;
use App\Models\Credential;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CredentialController extends Controller
{
    public function index(): View
    {
        return view('admin.credentials.index', [
            'credentials' => Credential::query()->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.credentials.create');
    }

    public function store(StoreCredentialRequest $request): RedirectResponse
    {
        $credential = Credential::query()->create($request->validated());

        return redirect()->route('admin.credentials.edit', $credential)->with('status', 'created');
    }

    public function edit(Credential $credential): View
    {
        return view('admin.credentials.edit', ['credential' => $credential]);
    }

    public function update(StoreCredentialRequest $request, Credential $credential): RedirectResponse
    {
        $attributes = $request->validated();

        if (! filled($attributes['password'] ?? null)) {
            unset($attributes['password']);
        }

        $credential->update($attributes);

        return redirect()->route('admin.credentials.edit', $credential)->with('status', 'updated');
    }

    public function destroy(Credential $credential): RedirectResponse
    {
        $credential->delete();

        return redirect()->route('admin.credentials')->with('status', 'deleted');
    }
}
