<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\Admin\PostService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class PostController extends Controller
{
     protected $Service;

    public function __construct(PostService $Service)
    {
        $this->Service = $Service;
    } 
    public function index(){
        return inertia('advertise/Index');
    }
    public function getData(){
        $data = $this->Service->getData();
        if ($data ->isEmpty()) {
            return API::notFound('No Data found');
        }
       
       return API::paginated($data);

    }
}
