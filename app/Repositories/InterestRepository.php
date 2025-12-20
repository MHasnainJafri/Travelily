<?php

namespace App\Repositories;

use App\Models\Interest;
use Illuminate\Http\Resources\Json\JsonResource;

class InterestRepository extends BaseRepository
{
    protected static string $model = Interest::class;

    protected static string $resourceClass = JsonResource::class;

    protected static array $searchable = [];

    protected static array $sortable = [];

    protected static array $defaultRelations = [];

    protected static array $allowedRelations = [];

    protected static int $defaultPerPage = 15;

    protected static ?string $cacheTag = null;

    public static function validationRules(string $operation): array
    {
        return $operation === 'store' ? [
            'name' => 'required|string|max:255',
        ] : [
            'name' => 'sometimes|required|string|max:255',
        ];
    }
}
