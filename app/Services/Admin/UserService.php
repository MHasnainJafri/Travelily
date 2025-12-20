<?php

namespace App\Services\Admin;

use App\Models\BucketList;
use App\Models\BucketListImage;
use App\Models\User;
use App\Helper\DataTableActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserService
{
    use DataTableActions;

    public function getRecord($id)
    {
        $user = User::with('roles', 'profile','interests','buddyInterests','travelActivities','travelWithOptions','traveledPlaces','recommendedPlaces','receivedReviews.reviewer','writtenReviews.reviewedUser','experiences','listings.media','boards')
        ->withCount([
            'traveledPlaces',
            'receivedReviews',
            'writtenReviews',
            'listings',
            'boards',
            'friendsOfMine',
            'friendOf'
        ])
        ->findOrFail($id);

        // If the user is not the authenticated user, check if the user has permission to view
        // if (Auth::id() !== $user->id && !Auth::user()->can('view', $user)) {
        //     abort(403, 'Unauthorized action.');
        // }

        return $user;
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
       
        
    }
    public function getUsers($role = 'traveller')
    {
        $query = User::query();

        if ($role) {
            //spatie role
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        return $data = $this->getProcessedData($query, request()->input('per_page', 10));
    }
}
