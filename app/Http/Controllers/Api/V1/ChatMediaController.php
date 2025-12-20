<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mhasnainjafri\RestApiKit\API;

class ChatMediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'type' => 'required|in:image,video,audio,file',
        ]);

        $file = $request->file('file');
        $type = $request->input('type');

        $allowedMimes = $this->getAllowedMimes($type);
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return API::error('Invalid file type for ' . $type, 422);
        }

        $path = $file->store('chat/' . $type . 's', 'public');
        
        $metadata = [
            'url' => Storage::disk('public')->url($path),
            'filename' => $file->getClientOriginalName(),
            'filesize' => $file->getSize(),
            'mimetype' => $file->getMimeType(),
        ];

        if ($type === 'audio' || $type === 'video') {
            $metadata['duration'] = $this->getMediaDuration($file);
        }

        if ($type === 'image') {
            $dimensions = getimagesize($file->path());
            if ($dimensions) {
                $metadata['width'] = $dimensions[0];
                $metadata['height'] = $dimensions[1];
            }
        }

        return API::success([
            'type' => $type,
            'metadata' => $metadata,
        ], 'File uploaded successfully');
    }

    public function uploadVoiceNote(Request $request): JsonResponse
    {
        $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,m4a,ogg,webm|max:10240', // 10MB max
            'duration' => 'sometimes|integer',
        ]);

        $file = $request->file('audio');
        $path = $file->store('chat/voice-notes', 'public');

        return API::success([
            'type' => 'audio',
            'metadata' => [
                'url' => Storage::disk('public')->url($path),
                'filename' => $file->getClientOriginalName(),
                'filesize' => $file->getSize(),
                'mimetype' => $file->getMimeType(),
                'duration' => $request->input('duration'),
                'is_voice_note' => true,
            ],
        ], 'Voice note uploaded successfully');
    }

    private function getAllowedMimes(string $type): array
    {
        return match ($type) {
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            'video' => ['video/mp4', 'video/quicktime', 'video/webm', 'video/avi'],
            'audio' => ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/webm', 'audio/mp4'],
            'file' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip',
                'text/plain',
            ],
            default => [],
        };
    }

    private function getMediaDuration($file): ?int
    {
        return null;
    }
}
