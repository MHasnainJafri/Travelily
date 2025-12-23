<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_type' => 'required|string',
            'target_id' => 'required|integer',
            'reason' => 'required|in:spam,fraud,harassment,inappropriate,scam,other',
            'description' => 'nullable|string',
        ]);

        $reportId = DB::table('reports')->insertGetId([
            'user_id' => auth()->id(),
            'target_type' => $validated['target_type'],
            'target_id' => $validated['target_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Report submitted successfully',
            'data' => ['id' => $reportId]
        ]);
    }
}
