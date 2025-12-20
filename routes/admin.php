<?php

use App\Http\Controllers\Admin\AdvertisingController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BucketListController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\JamBoardController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Group routes under 'admin' prefix and apply 'role:admin' middleware


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');


    Route::prefix('admin')->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
        
        // Jamboard
        Route::get('/jamboard', [JamBoardController::class, 'index'])->name('admin.jamboard.index');
        Route::get('/jamboard/{id}', [JamBoardController::class, 'show'])->name('admin.jamboard.view');
        
        // Advertisement & Posts
        Route::get('/advertisement', [AdvertisingController::class, 'index'])->name('admin.advertisement.index');
        Route::get('/posts', [PostController::class, 'index'])->name('admin.post.index');

        // Trips
        Route::get('/trips', [TripController::class, 'index'])->name('admin.trips.index');
        Route::get('/trips/{id}', [TripController::class, 'show'])->name('admin.trips.show');
        Route::delete('/trips/{id}', [TripController::class, 'destroy'])->name('admin.trips.destroy');

        // Listings
        Route::get('/listings', [ListingController::class, 'index'])->name('admin.listings.index');
        Route::get('/listings/{id}', [ListingController::class, 'show'])->name('admin.listings.show');
        Route::delete('/listings/{id}', [ListingController::class, 'destroy'])->name('admin.listings.destroy');

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('admin.bookings.show');
        Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');

        // Plans
        Route::get('/plans', [PlanController::class, 'index'])->name('admin.plans.index');
        Route::get('/plans/create', [PlanController::class, 'create'])->name('admin.plans.create');
        Route::post('/plans', [PlanController::class, 'store'])->name('admin.plans.store');
        Route::get('/plans/{id}', [PlanController::class, 'show'])->name('admin.plans.show');
        Route::get('/plans/{id}/edit', [PlanController::class, 'edit'])->name('admin.plans.edit');
        Route::put('/plans/{id}', [PlanController::class, 'update'])->name('admin.plans.update');
        Route::delete('/plans/{id}', [PlanController::class, 'destroy'])->name('admin.plans.destroy');

        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');
        Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show'])->name('admin.subscriptions.show');
        Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('admin.subscriptions.destroy');

        // Tasks
        Route::get('/tasks', [TaskController::class, 'index'])->name('admin.tasks.index');
        Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('admin.tasks.show');
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');

        // Interests
        Route::get('/interests', [InterestController::class, 'index'])->name('admin.interests.index');
        Route::get('/interests/create', [InterestController::class, 'create'])->name('admin.interests.create');
        Route::post('/interests', [InterestController::class, 'store'])->name('admin.interests.store');
        Route::get('/interests/{id}', [InterestController::class, 'show'])->name('admin.interests.show');
        Route::get('/interests/{id}/edit', [InterestController::class, 'edit'])->name('admin.interests.edit');
        Route::put('/interests/{id}', [InterestController::class, 'update'])->name('admin.interests.update');
        Route::delete('/interests/{id}', [InterestController::class, 'destroy'])->name('admin.interests.destroy');

        // Amenities
        Route::get('/amenities', [AmenityController::class, 'index'])->name('admin.amenities.index');
        Route::get('/amenities/create', [AmenityController::class, 'create'])->name('admin.amenities.create');
        Route::post('/amenities', [AmenityController::class, 'store'])->name('admin.amenities.store');
        Route::get('/amenities/{id}', [AmenityController::class, 'show'])->name('admin.amenities.show');
        Route::get('/amenities/{id}/edit', [AmenityController::class, 'edit'])->name('admin.amenities.edit');
        Route::put('/amenities/{id}', [AmenityController::class, 'update'])->name('admin.amenities.update');
        Route::delete('/amenities/{id}', [AmenityController::class, 'destroy'])->name('admin.amenities.destroy');

        // Bucket Lists
        Route::get('/buckets', [BucketListController::class, 'index'])->name('admin.buckets.index');
        Route::get('/buckets/{id}', [BucketListController::class, 'show'])->name('admin.buckets.show');
        Route::delete('/buckets/{id}', [BucketListController::class, 'destroy'])->name('admin.buckets.destroy');
    });
});




// =======================API Section=================

Route::prefix('admin/api')->group(function () {
    Route::get('/get-users/{role}', [UserController::class, 'getUsers'])->name('admin.users.getUsers');
    Route::get('/get-jam', [JamBoardController::class, 'getData'])->name('admin.jam.getData');
    Route::get('/get-advertising', [AdvertisingController::class, 'getData'])->name('admin.advertising.getData');
    Route::get('/get-posts', [PostController::class, 'getData'])->name('admin.posts.getData');
    Route::get('/get-trips', [TripController::class, 'getData'])->name('admin.trips.getData');
    Route::get('/get-listings', [ListingController::class, 'getData'])->name('admin.listings.getData');
    Route::get('/get-bookings', [BookingController::class, 'getData'])->name('admin.bookings.getData');
    Route::get('/get-plans', [PlanController::class, 'getData'])->name('admin.plans.getData');
    Route::get('/get-subscriptions', [SubscriptionController::class, 'getData'])->name('admin.subscriptions.getData');
    Route::get('/get-tasks', [TaskController::class, 'getData'])->name('admin.tasks.getData');
    Route::get('/get-interests', [InterestController::class, 'getData'])->name('admin.interests.getData');
    Route::get('/get-amenities', [AmenityController::class, 'getData'])->name('admin.amenities.getData');
    Route::get('/get-buckets', [BucketListController::class, 'getData'])->name('admin.buckets.getData');
});