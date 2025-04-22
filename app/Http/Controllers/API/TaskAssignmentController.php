<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Validator;
 
class TaskAssignmentController extends Controller
{   

    public function assignUsers(Request $request)
    {   
        $request->validate([
            'assign_ids' => 'required|array',
        ]);

        $response = [];

        foreach ($request->assign_ids as $email) {
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response[] = [
                    'assigned_id' => $email,
                    'message' => 'Enter valid email id',
                ];
                continue;
            }

            // Find user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $response[] = [
                    'assigned_id' => $email,
                    'message' => 'User not found in system',
                ];
                continue;
            }

            $task = new TaskAssignment();
            if ($task->where('assigned_to', $user->id)->where('task_id',$request->task_id)->exists()) {
                $response[] = [
                    'assigned_id' => $email,
                    'message' => 'Task already assigned to this user',
                ];
                continue;
            }

            // Assign the user
            $taskAssignment = new TaskAssignment();
            $taskAssignment->task_id = $request->task_id;
            $taskAssignment->assigned_to = $user->id;
            $taskAssignment->assigned_by = $request->user_id;
            $taskAssignment->save();

            $response[] = [
                'assigned_id' => $email,
                'message' => 'Task assigned',
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Task assignment process completed',
            'data' => $response,
        ], 200);
    }

    public function listAssignees($taskId){

        $task = Task::where('id', $taskId)->with('assignees')->get(['id','title','description','status','priority','due_date']);

        $assignees = $task->map(function ($user) {
            return $user->assignees->makeHidden('pivot');
        });

        if (count($task) > 0) {
            
            $task = response()->json(['success' => true, 'message' => 'List of assignees', 'data' => $task], 200);            

        }else{
            $task = response()->json(['success' => false, 'message' => 'Task not found'], 404); 
        }

        return $task;
    }

    public function removeAssignee($taskId, $userId){

        $taskAssignment = TaskAssignment::where('task_id', $taskId)
                      ->where('assigned_to', $userId)
                      ->first();

        if ($taskAssignment) {

            TaskAssignment::where('id', $taskAssignment->id)->delete();

        }else{
            
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'User removed from task.'], 200);
    }

}
