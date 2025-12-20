<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\JamService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class JamBoardController extends Controller
{
     protected $userService;

    public function __construct(JamService $userService)
    {
        $this->userService = $userService;
    }
    public function index(){
        return inertia('Jam/Index');
    }
    public function getData(){
        $data = $this->userService->getData();
        if ($data ->isEmpty()) {
            return API::notFound('No users found');
        }
       
       return API::paginated($data);

    }

     public function show($id){
        $record= $this->userService->getRecord($id);
        return inertia( 'Jam/View',['record'=>$record]);
    }
}
