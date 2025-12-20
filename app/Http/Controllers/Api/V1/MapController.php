<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\MapService;
use Illuminate\Http\Request;

class MapController extends Controller
{
    protected $mapService;

    public function __construct(MapService $mapService)
    {
        $this->mapService = $mapService;
    }

    public function getJamMapData($jamId)
    {
        try {
            $mapData = $this->mapService->getJamMapData($jamId);
            return response()->json(['map_data' => $mapData]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}