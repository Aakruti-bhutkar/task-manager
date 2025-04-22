<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskAssignmentController extends Controller
{
    
    // Task list 
    public function index()
    {   
        $taskAssignment = \App\Models\Task::with(['taskAssignedBy.assignedBy', 'assignees'])->get();
        return view('task_assignment.index', compact('taskAssignment'));
    }

    public function create($id)
    {   
        $task = App\Models\Task::find($id);
        $users = \App\Models\User::role('employee')->get();
        return view('task_assignment.add', compact('task','users'));
    }

    public function add($id)
    {
        $users = \App\Models\User::role('employee')->get();
        $task = \App\Models\Task::find($id);
        return view('task_assignment.add', compact('users','task'));
    }
    
    // Save details of employee
    public function store(Request $request, \App\Models\Task $task)
    {   
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $assignedBy = Auth::id();
        $responses = [];

        foreach ($request->user_ids as $userId) {
            $alreadyAssigned = \App\Models\TaskAssignment::where('task_id', $task->id)
                ->where('assigned_to', $userId)
                ->exists();

            if ($alreadyAssigned) {
                $responses[] = [
                    'user_id' => $userId,
                    'message' => 'Already assigned',
                ];
                continue;
            }

            \App\Models\TaskAssignment::create([
                'task_id'     => $task->id,
                'assigned_to' => $userId,
                'assigned_by' => $assignedBy,
            ]);

            $responses[] = [
                'user_id' => $userId,
                'message' => 'Assigned successfully',
            ];
        }

        return redirect()->route('task-assignment.index')->with('success', 'Users assigned successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = \App\Models\User::all();
        $task = \App\Models\Task::find($id);
        return view('task_assignment.edit', compact('users','task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $assignment = \App\Models\TaskAssignment::findOrFail($id);
        $assignment->delete(); 
        return redirect()->route('task-assignment.index')
                         ->with('success', 'Assignment deleted successfully.');
    }

    // Get remove user page
    public function getRemoveUser($taskId){
        
        $task = \App\Models\Task::with('assignees')->findOrFail($taskId);
        return view('task_assignment.edit', compact('task'));
    }

    // Remove users from task
    public function removeUsers(Request $request, $taskId)
    {
        $request->validate([
            'user_ids' => 'required|array', 
        ]);

        foreach ($request->user_ids as $userId) {
           
            \App\Models\TaskAssignment::where('task_id', $taskId)
                                      ->where('assigned_to', $userId)
                                      ->delete();
        }
        return redirect()->route('task-assignment.index')->with('success', 'Selected users removed from task successfully.');
    }

}
