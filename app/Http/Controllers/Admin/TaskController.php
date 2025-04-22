<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskUpdated;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $tasks = \App\Models\Task::with('assignees') // eager load assignees
                ->whereNull('deleted_at')
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
                ->when($request->due_date, fn($q) => $q->whereDate('due_date', $request->due_date))
                ->orderBy('due_date')
                ->get();
        } else {

            $tasks = \App\Models\Task::with('assignees') 
                ->whereNull('deleted_at')
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
                ->when($request->due_date, fn($q) => $q->whereDate('due_date', $request->due_date))
                ->whereHas('assignees', function ($query) use ($user) {
                    $query->where('assigned_to', $user->id);
                })
                ->orderBy('due_date')
                ->get();
        }
        
        return view('tasks.index', compact('tasks'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $users = \App\Models\User::all();
        return view('tasks.add', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required|date',
        ]);

        $existingTask = \App\Models\Task::where('title', $request->title)->first();

        if ($existingTask) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Task with this title already exists.');
        }

        \App\Models\Task::create($request->only('title', 'description', 'due_date'));
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
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
        $task = \App\Models\Task::with('assignees')->findOrFail($id);
        $users = \App\Models\User::all();
        return view('tasks.edit', compact('task', 'users'));
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
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $request->validate([
                'title' => 'required',
                'status' => 'required',
                'priority' => 'required',
                'due_date' => 'required|date',
            ]);

            $task = \App\Models\Task::findOrFail($id);
            $task->update($request->only('title', 'description', 'status', 'priority', 'due_date'));

        }else{

            $request->validate([               
                'status' => 'required',               
            ]);

            $task = \App\Models\Task::findOrFail($id);
            $task->update($request->only('status'));
        }

        // Broadcast the event
        broadcast(new TaskUpdated($task));
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Models\Task $task)
    {   
        $task->delete(); 
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

}
