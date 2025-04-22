<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{   
    // List of task 
    public function index(Request $request)
    {
        $query = Task::query()->with(['user:id,name,email']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->orderBy('due_date')->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json(['success' => true, 'message' => 'Task List', 'data' => $tasks], 200);
    }

    public function show($id)
    {
        $task = Task::with(['user:id,name,email'])->find($id);
        if (!$task) return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        return response()->json(['success' => true, 'message' => 'Task Details', 'data' => $task], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $taskExist = Task::where('title', 'like', '%' . $request->title . '%')->first();
        if($taskExist){
            return response()->json(['success' => false, 'message' => 'Task already exist'], 422);
        }
        // Automatically assign to the authenticated user
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
            'priority' => isset($request->priority) ? $request->priority : 'medium',
            'due_date' => $request->due_date,
            'user_id' => $request->user_id, // Auto-set owner
        ]);

        return response()->json(['success' => true, 'message' => 'Task created successfully', 'task' => $task]);
    }

    public function update(Request $request, $id){

        $task = Task::find($id);
        if (!$task) return response()->json(['success' => false, 'message' => 'Task not found'], 404);

        $taskExist = Task::where('title', 'like', '%' . $request->title . '%')->where('id', '!=', $id)->first();
        if($taskExist){
            return response()->json(['success' => false, 'message' => 'Task already exist'], 422);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }

        $task->update($request->all());
        return response()->json(['success' => true, 'message' => 'Task updated successfully', 'task' => $task]);
    }

    public function destroy($id){

        $task = Task::find($id);
        if (!$task) return response()->json(['success' => false, 'message' => 'Task not found'], 404);

        $task->delete();
        return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
    }


}
