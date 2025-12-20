<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['jam_id', 'title', 'description', 'completed','status','due_date'];

    public function board()
    {
        return $this->belongsTo(Jam::class);
    }
    public function jam()
    {
        return $this->belongsTo(Jam::class);
    }
    public function assignees()
{
    return $this->belongsToMany(User::class, 'task_assignees')
                ->withTimestamps();
}
}
