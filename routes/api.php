<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\V1\BucketListController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ChatMediaController;
use App\Http\Controllers\Api\V1\FriendshipController;
use App\Http\Controllers\Api\V1\GoogleCalendarController;
use App\Http\Controllers\Api\V1\JamController;
use App\Http\Controllers\Api\V1\JamGuideController;
use App\Http\Controllers\Api\V1\JamInvitationController;
use App\Http\Controllers\Api\V1\JamItineraryController;
use App\Http\Controllers\Api\V1\JamUserController;
use App\Http\Controllers\Api\V1\MapController;
use App\Http\Controllers\Api\V1\PostCommentController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\SocialLoginController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserMediaController;
use App\Http\Controllers\Api\V1\UserPlaceController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mhasnainjafri\RestApiKit\API;
 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('social-login', [SocialLoginController::class, 'login'])->middleware('guest');

    Route::middleware('auth:api')->group(function () {
        Route::post('/profile/update', [AuthController::class, 'updateProfile']);
        Route::get('/users/{id?}', [UserController::class, 'getProfile']);
        Route::get('userslist', [UserController::class, 'userslist']);
        Route::post('updateLocation', [UserController::class, 'updateLocation']);
        Route::post('/users/gallery', [UserMediaController::class, 'addToGallery']);
        Route::post('/user/traveled-places', [UserPlaceController::class, 'storeTraveledPlaces']);
        Route::post('/user/recommended-places', [UserPlaceController::class, 'storeRecommendedPlaces']);

        // post
        Route::apiResource('posts', PostController::class);
        Route::apiResource('comments', PostCommentController::class);

        // Friendship routes
        Route::get('/friendships', [FriendshipController::class, 'getFriends']);
        Route::get('/friendships/requests', [FriendshipController::class, 'getPendingRequests']);
        Route::get('/friendships/requests/sent', [FriendshipController::class, 'getSentRequests']);
        Route::get('/friendships/followers', [FriendshipController::class, 'getFollowers']);
        Route::get('/friendships/following', [FriendshipController::class, 'getFollowing']);
        Route::get('/friendships/status/{userId}', [FriendshipController::class, 'getRelationshipStatus']);
        Route::post('/friendships/send/{userId}', [FriendshipController::class, 'sendRequest']);
        Route::post('/friendships/accept/{userId}', [FriendshipController::class, 'acceptRequest']);
        Route::post('/friendships/reject/{userId}', [FriendshipController::class, 'rejectRequest']);
        Route::post('/friendships/cancel/{userId}', [FriendshipController::class, 'cancelRequest']);
        Route::post('/friendships/unfriend/{userId}', [FriendshipController::class, 'unfriend']);
        Route::post('/friendships/follow/{userId}', [FriendshipController::class, 'follow']);
        Route::post('/friendships/unfollow/{userId}', [FriendshipController::class, 'unfollow']);
        Route::get('/users/search', [FriendshipController::class, 'searchUsers']);

        // Invitation routes
        Route::post('/jams/{jamId}/invitations', [JamInvitationController::class, 'sendInvitations']);
        Route::post('/invitations/{invitationId}/accept', [JamInvitationController::class, 'acceptInvitation']);
        Route::post('/invitations/{invitationId}/reject', [JamInvitationController::class, 'rejectInvitation']);
        // Trip Routes

        // Route::apiResource('trips', TripController::class);
        Route::post('trips', [\App\Http\Controllers\Api\V1\TripController::class, 'store']);
        Route::get('trips/my', [\App\Http\Controllers\Api\V1\TripController::class, 'getMyTrips']);
        Route::get('trips/search', [\App\Http\Controllers\Api\V1\TripController::class, 'searchTrips']);
        Route::post('trips/{tripId}/join', [\App\Http\Controllers\Api\V1\TripController::class, 'sendJoinRequest']);
        Route::get('trips/{tripId}', [\App\Http\Controllers\Api\V1\TripController::class, 'getTripDetails']);
        Route::put('trips/{tripId}/permissions/{userId}', [\App\Http\Controllers\Api\V1\TripController::class, 'updatePermissions']);
        Route::post('trips/{tripId}/lock', [\App\Http\Controllers\Api\V1\TripController::class, 'lockTrip']);

        // Jamboard api
        Route::post('/jams/{jamId}/flights', [JamItineraryController::class, 'addFlight']);
        Route::post('/jams/{jamId}/accommodations', [JamItineraryController::class, 'addAccommodation']);
        Route::post('/jams/{jamId}/activities', [JamItineraryController::class, 'addActivity']);
        Route::put('/jams/{jamId}/users/{userId}/permissions', [JamUserController::class, 'updatePermissions']);
        Route::get('/jams/my', [JamController::class, 'getMyJams']);
        Route::get('/jams', [JamController::class, 'getJams']);
        Route::get('/jams/search', [JamController::class, 'searchJams']);
        Route::post('/jams/{jamId}/join', [JamController::class, 'sendJoinRequest']);
        Route::post('/jams/{jamId}/experiences', [JamItineraryController::class, 'addExperience']);
        Route::post('/jams/{jamId}/guides', [JamGuideController::class, 'assignGuide']);
        Route::get('/jams/{jamId}', [JamController::class, 'getJamDetails']);
        Route::post('/jams/{jamId}/lock', [JamController::class, 'lockJam']);

        // Jamboard tasks

        Route::post('/jams/{jamId}/tasks', [TaskController::class, 'createTask']);
        Route::put('/tasks/{taskId}', [TaskController::class, 'updateTask']);
        Route::delete('/tasks/{taskId}', [TaskController::class, 'deleteTask']);
        Route::post('/tasks/{taskId}/assign', [TaskController::class, 'assignUserToTask']);
        Route::post('/tasks/{taskId}/unassign', [TaskController::class, 'removeUserFromTask']);

        //Bucket list apis
        Route::get('/bucket-lists', [BucketListController::class, 'getBucketLists']);
        Route::get('/bucket-lists/{id}', [BucketListController::class, 'getBucketList']);
        Route::post('/bucket-lists', [BucketListController::class, 'createBucketList']);
        Route::put('/bucket-lists/{id}', [BucketListController::class, 'updateBucketList']);
        Route::delete('/bucket-lists/{id}', [BucketListController::class, 'deleteBucketList']);

        Route::get('/jams/{jamId}/map', [MapController::class, 'getJamMapData']);
        //google calender api
        Route::get('/google-calendar/auth', [GoogleCalendarController::class, 'redirectToGoogle']);
        Route::get('/google-callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
        Route::post('/jams/{jamId}/sync-calendar', [GoogleCalendarController::class, 'syncJamToCalendar']);
        Route::get('/calendar/events', [GoogleCalendarController::class, 'getCalendarEvents']);

        // Booking Routes
        Route::apiResource('bookings', BookingController::class);
        Route::patch('bookings/{id}/status', [BookingController::class, 'updateStatus']);

        // Jam Routes
        Route::post('/jams', [JamController::class, 'store']);

        // Chat Routes
        Route::prefix('chat')->group(function () {
            // Conversations
            Route::get('/conversations', [ChatController::class, 'conversations']);
            Route::get('/conversations/personal', [ChatController::class, 'personalConversations']);
            Route::get('/conversations/jam', [ChatController::class, 'jamConversations']);
            Route::get('/conversations/{conversationId}', [ChatController::class, 'show']);
            
            // Start conversations
            Route::post('/conversations/personal/{friendId}', [ChatController::class, 'startPersonalChat']);
            Route::post('/conversations/jam/{jamId}', [ChatController::class, 'startJamChat']);
            
            // Messages
            Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'messages']);
            Route::post('/conversations/{conversationId}/messages', [ChatController::class, 'sendMessage']);
            Route::put('/messages/{messageId}', [ChatController::class, 'editMessage']);
            Route::delete('/messages/{messageId}', [ChatController::class, 'deleteMessage']);
            
            // Message actions
            Route::post('/conversations/{conversationId}/read', [ChatController::class, 'markAsRead']);
            Route::post('/conversations/{conversationId}/typing', [ChatController::class, 'typing']);
            Route::get('/conversations/{conversationId}/search', [ChatController::class, 'searchMessages']);
            
            // Conversation settings
            Route::post('/conversations/{conversationId}/mute', [ChatController::class, 'toggleMute']);
            Route::post('/conversations/{conversationId}/pin', [ChatController::class, 'togglePin']);
            Route::post('/conversations/{conversationId}/leave', [ChatController::class, 'leaveConversation']);
            Route::post('/conversations/{conversationId}/participants', [ChatController::class, 'addParticipant']);
            
            // Media uploads
            Route::post('/upload', [ChatMediaController::class, 'upload']);
            Route::post('/upload/voice-note', [ChatMediaController::class, 'uploadVoiceNote']);
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::post('/cards', [CardController::class, 'addCard']);
        Route::delete('/cards/{card}', [CardController::class, 'deleteCard']);
        Route::get('/cards', [CardController::class, 'listCards']);

        Route::get('/plans', [PlanController::class, 'index']);
        Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
        Route::get('/subscription', [SubscriptionController::class, 'getCurrentSubscription']);
    });
});

// API::SUCCESS;
Route::restifyAuth();
