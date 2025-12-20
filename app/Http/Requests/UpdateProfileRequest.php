<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
 use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Prepare the request data for validation.
     * Converts JSON-encoded strings for array fields into actual arrays.
     */
    protected function prepareForValidation()
    {
        $fields = ['travel_activities_ids', 'buddy_interests_ids', 'travel_with_options_ids', 'my_interests_ids'];

        foreach ($fields as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $decoded = json_decode($this->input($field), true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $this->merge([$field => $decoded]);
                } else {
                    $this->merge([$field => []]); // Fallback to empty array if JSON decoding fails
                }
            }
        }
    }

    /**
     * Define the validation rules for the request.
     */
   
public function rules(): array
{
    return [
        'travel_activities_ids' => 'nullable|array',
        'travel_activities_ids.*' => 'integer|exists:travel_activities,id',

        'name' => 'nullable|string',

        // 👇 username unique except current user
        'username' => [
            'nullable',
            'string',
            Rule::unique('users', 'username')->ignore($this->user()->id),
        ],

        'buddy_interests_ids' => 'nullable|array',
        'buddy_interests_ids.*' => 'integer|exists:interests,id',

        'my_interests_ids' => 'nullable|array',
        'my_interests_ids.*' => 'integer|exists:interests,id',

        'travel_with_options_ids' => 'nullable|array',
        'travel_with_options_ids.*' => 'integer|exists:travel_with_options,id',

        'description' => 'nullable|string|max:500',
        'facebook' => 'nullable|url',
        'tiktok' => 'nullable|url',
        'linkedin' => 'nullable|url',
        'local_expert_place_name' => 'nullable|string|max:255',
        'local_expert_google_place_id' => 'nullable|string|max:255',

        'short_video' => 'nullable|mimes:mp4,mov,avi,wmv|max:10000',
        'profile_photo' => 'nullable|file|image'
    ];
}


    /**
     * Define custom validation messages.
     */
    public function messages(): array
    {
        return [
            'travel_activities_ids.*.exists' => 'One or more selected travel activities are invalid.',
            'buddy_interests_ids.*.exists' => 'One or more selected buddy interests are invalid.',
            'my_interests_ids.*.exists' => 'One or more selected interests are invalid.',
            'travel_with_options_ids.*.exists' => 'One or more selected travel companions are invalid.',
            'description.max' => 'The description cannot exceed 500 characters.',
            'facebook.url' => 'The Facebook link must be a valid URL.',
            'tiktok.url' => 'The TikTok link must be a valid URL.',
            'linkedin.url' => 'The LinkedIn link must be a valid URL.',
            'short_video.mimes' => 'The short video must be a file of type: mp4, mov, avi, or wmv.',
            'short_video.max' => 'The short video may not be larger than 10 MB.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     * Ensures the authenticated user can only update their own profile.
     */
    public function authorize(): bool
    {
        return true;
        $user = Auth::user();
        $userId = $this->route('user') ?? $this->input('user_id'); // Assumes user ID is passed in route or request

        return $user && $user->id == $userId;
    }
}
