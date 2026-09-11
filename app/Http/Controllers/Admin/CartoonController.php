<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartoonRequest;
use App\Models\Cartoon;
use App\Models\Country;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartoonController extends Controller
{
    public function index(): View
    {
        return view('admin.cartoons.index', [
            'cartoons' => Cartoon::query()->with(['files', 'genres', 'countries'])->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.cartoons.create', $this->catalog());
    }

    public function store(StoreCartoonRequest $request): RedirectResponse
    {
        $cartoon = Cartoon::query()->create($request->cartoonAttributes());
        $cartoon->genres()->sync($request->genreIds());
        $cartoon->countries()->sync($request->countryIds());
        $this->syncPoster($request, $cartoon);

        return redirect()
            ->route('admin.cartoons.edit', $cartoon)
            ->with('status', 'cartoon-created');
    }

    public function edit(Cartoon $cartoon): View
    {
        $cartoon->load(['files', 'genres', 'countries']);

        return view('admin.cartoons.edit', [
            'cartoon' => $cartoon,
            ...$this->catalog(),
        ]);
    }

    public function update(StoreCartoonRequest $request, Cartoon $cartoon): RedirectResponse
    {
        $cartoon->update($request->cartoonAttributes());
        $cartoon->genres()->sync($request->genreIds());
        $cartoon->countries()->sync($request->countryIds());
        $this->syncPoster($request, $cartoon);

        return redirect()
            ->route('admin.cartoons.edit', $cartoon)
            ->with('status', 'cartoon-updated');
    }

    public function destroy(Cartoon $cartoon): RedirectResponse
    {
        $cartoon->delete();

        return redirect()
            ->route('admin.cartoons')
            ->with('status', 'cartoon-deleted');
    }

    private function syncPoster(StoreCartoonRequest $request, Cartoon $cartoon): void
    {
        if ($request->boolean('remove_poster')) {
            $cartoon->deletePosterFile();
            $cartoon->update(['poster' => null]);

            return;
        }

        if ($request->hasFile('poster')) {
            $cartoon->storePoster($request->file('poster'));
        }
    }

    /**
     * @return array{genres: Collection<int, Genre>, countries: Collection<int, Country>}
     */
    private function catalog(): array
    {
        return [
            'genres' => Genre::query()->orderBy('name')->get(),
            'countries' => Country::query()->orderBy('name')->get(),
        ];
    }
}
