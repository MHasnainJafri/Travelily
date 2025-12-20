<?php

namespace App\Services\Admin;

use App\Models\Advertisement;
use App\Helper\DataTableActions;
use App\Models\Post;

class PostService
{
    use DataTableActions;
    public function getData()
    {
        $query = Post::query()->with(['checkIn','taggedBoards:id,name','taggedUsers:id,name,username','labels'])
            ;

        return $data = $this->getProcessedData($query, request()->input('per_page', 10));
    }
}
