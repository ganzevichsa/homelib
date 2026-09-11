<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnimatedSeasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $animatedSeries = $this->route('animatedSeries');

        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                Rule::unique('animated_seasons', 'number')->where('animated_series_id', $animatedSeries?->id),
            ],
            'title' => ['nullable', 'string', 'max:255'],
        ];
    }
}
