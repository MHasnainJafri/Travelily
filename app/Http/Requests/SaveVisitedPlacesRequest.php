<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveVisitedPlacesRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Only authenticated users can save visited places
    }

    protected function prepareForValidation()
    {
        // Decode visited_places if it is a JSON string
        if (is_string($this->visited_places)) {
            $decoded = json_decode($this->visited_places, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->merge(['visited_places' => $decoded]);
            }
        }

    }

    public function rules()
    {
        return [
            'visited_places' => 'required|array', // Ensure exactly 5 places
            'visited_places.*.place_name' => 'required|string|max:255',
            'visited_places.*.address' => 'nullable|string|max:255',
            'visited_places.*.latitude' => 'required|numeric|between:-90,90',
            'visited_places.*.longitude' => 'required|numeric|between:-180,180',
            'visited_places.*.rank' => 'nullable',
            'visited_places.*.google_place_id' => 'nullable|string|max:255',
        ];
    }
}
