<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'assigned_to', 'assigned_by'];


    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignees(){
        
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'assigned_to');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
