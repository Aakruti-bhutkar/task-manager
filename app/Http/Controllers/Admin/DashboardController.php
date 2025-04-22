<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Notifications\TaskAssigned;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    // Get dashboard details
    public function index()
    {   
        $user = Auth::user();

        $today = \Carbon\Carbon::today()->toDateString();

        $overdueTasks = Task::where('due_date', '<', $today)->where('status', '!=', 'completed')->get();

        // Check user role
        if ($user->hasRole('admin')) {
            
            $totalTasks = Task::count();
            $pendingTasks = Task::where('status', 'pending')->count();
            $inProgressTasks = Task::where('status', 'in_progress')->count();
            $completedTasks = Task::where('status', 'completed')->count();

            $users = User::whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'admin'); 
                })
                ->get();

            $userPerformance = [];

            foreach ($users as $user) {
                
                $completedTaskCount = DB::table('task_assignments')
                                        ->join('tasks', 'tasks.id', '=', 'task_assignments.task_id')
                                        ->where('task_assignments.assigned_to', $user->id)
                                        ->where('tasks.status', 'completed')
                                        ->count();

                $userPerformance[] = [
                    'name' => $user->name,
                    'completed_count' => $completedTaskCount, // This will be 0 if no completed tasks
                ];
            }

            return view('dashboard', compact('totalTasks', 'pendingTasks', 'inProgressTasks', 'completedTasks', 'userPerformance', 'overdueTasks'));
        }

        $tasks = Task::whereHas('assignees', function ($query) {
            $query->where('assigned_to', Auth::id());
        })->get();

        return view('dashboard', compact('tasks', 'overdueTasks'));
    }
    
}

