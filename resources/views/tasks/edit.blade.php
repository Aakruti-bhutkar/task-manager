@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Task</h5>
            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light">← Back to Task List</a>
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required @if(Auth::user()->hasRole('employee')) disabled  @endif>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Due Date <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', \Carbon\Carbon::parse($task->due_date)->format('Y-m-d')) }}" required @if(Auth::user()->hasRole('employee')) disabled @endif>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $task->description) }}</textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" >Priority</label>
                        <select name="priority" class="form-select" required @if(Auth::user()->hasRole('employee')) disabled @endif>
                            <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
