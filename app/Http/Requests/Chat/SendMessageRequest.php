<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|in:text,image,video,audio,file,location',
            'body' => 'required_if:type,text|nullable|string|max:5000',
            'metadata' => 'sometimes|array',
            'metadata.url' => 'required_if:type,image,video,audio,file|nullable|url',
            'metadata.filename' => 'sometimes|string|max:255',
            'metadata.filesize' => 'sometimes|integer',
            'metadata.mimetype' => 'sometimes|string|max:100',
            'metadata.duration' => 'sometimes|integer',
            'metadata.thumbnail' => 'sometimes|url',
            'metadata.latitude' => 'required_if:type,location|nullable|numeric|between:-90,90',
            'metadata.longitude' => 'required_if:type,location|nullable|numeric|between:-180,180',
            'metadata.address' => 'sometimes|string|max:500',
            'reply_to_id' => 'sometimes|nullable|exists:messages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required_if' => 'Message body is required for text messages.',
            'metadata.url.required_if' => 'Media URL is required for media messages.',
            'metadata.latitude.required_if' => 'Latitude is required for location messages.',
            'metadata.longitude.required_if' => 'Longitude is required for location messages.',
        ];
    }
}
