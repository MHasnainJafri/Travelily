<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BucketListImage extends Model
{
    use HasFactory;

    protected $fillable = ['bucket_list_id', 'image_path'];

    public function bucketList()
    {
        return $this->belongsTo(BucketList::class);
    }
}