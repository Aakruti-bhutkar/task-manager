@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Task List</h2>
    @if(Auth::user()->hasRole('admin'))
    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">+ Create Task</a>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('tasks.index') }}" class="row mb-4">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="priority" class="form-select">
                <option value="">All Priorities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="due_date" class="form-control" placeholder="Due Date">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Task Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
                <!-- <th>Assigned Users</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td><span class="badge bg-{{ $task->status == 'pending' ? 'warning' : ($task->status == 'in_progress' ? 'info' : 'success') }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span></td>
                    <td><span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($task->priority) }}
                    </span></td>
                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                    <!-- <td>{{ $task->assignees->pluck('name')->join(', ') }}</td> -->
                    <td>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        @if(Auth::user()->hasRole('admin'))
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
 
@endsection
