<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class UserController extends Controller
{
     protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(){
        return inertia('Users/Index',['role'=>request()->get('role', 'traveller')]);
    }
    public function show($id){
        $user= $this->userService->getRecord($id);
        return inertia('Users/View',['user'=>$user]);
    }
    public function destroy($id){
          $this->userService->destroy($id);
              return redirect()->back()->with('success', 'User deleted successfully.');

    }
    public function getUsers($role = 'traveller'){
        $users = $this->userService->getUsers($role);
        if ($users->isEmpty()) {
            return API::notFound('No users found');
        }
        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'profile_photo' => $user->profile_photo,
                'created_at' => $user->created_at->diffForHumans(),
                'updated_at' => $user->updated_at->diffForHumans(),
            ];
        });
       return API::paginated($users);

    }
}
