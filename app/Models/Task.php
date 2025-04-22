<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'due_date' => 'datetime',
    ];
    
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'user_id',
    ];

    public function user(){

         return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'assigned_to')->select('users.id', 'users.name', 'users.email');
    }

    public function taskAssignedBy()
    {
        return $this->hasOne(TaskAssignment::class, 'task_id'); // Task has many task assignments
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'task_id');
    }

}
