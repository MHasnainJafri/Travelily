<?php

namespace App\Services\Admin;

use App\Models\BucketList;
use App\Models\BucketListImage;
use App\Models\User;
use App\Helper\DataTableActions;
use App\Models\Jam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JamService
{
    use DataTableActions;
    public function getData()
    {
        $query = Jam::query();

        return $data = $this->getProcessedData($query, request()->input('per_page', 10));
    }
    public function getRecord($id)
    {
        $jam = Jam::with(['media','guides','creator','users','itineraries','flights','tasks','members'])->findOrFail($id);

        // If the user is not the authenticated user, check if the user has permission to view
        // if (Auth::id() !== $jam->user_id && !Auth::user()->can('view', $jam)) {
        //     abort(403, 'Unauthorized action.');
        // }

        return $jam;
    }
}
