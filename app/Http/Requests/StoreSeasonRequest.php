<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeasonRequest extends FormRequest
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
        $series = $this->route('series');

        return [
            'number' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                Rule::unique('seasons', 'number')->where('series_id', $series?->id),
            ],
            'title' => ['nullable', 'string', 'max:255'],
        ];
    }
}
