@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Remove Users from Task</h5>
            <a href="{{ route('task-assignment.index') }}" class="btn btn-sm btn-light">← Back to Task List</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Assignment Form -->
            <form method="POST" action="{{ route('task-assignment.removeUsers', $task->id) }}">
                @csrf

                <!-- Task Title -->
                <div class="mb-3">
                    <label class="form-label">Task Title</label>
                    <input type="text" class="form-control" value="{{ $task->title }}" readonly>
                </div>

                <!-- Assigned Users -->
                <div class="mb-3">
                    <label for="user_ids" class="form-label">Select Users to Remove</label>

                    <!-- List of assigned users with checkboxes -->
                    <div class="d-flex flex-column">
                        @foreach ($task->assignees as $assignment)
                            <div class="form-check">
                                <input type="checkbox" name="user_ids[]" value="{{ $assignment->id }}" class="form-check-input" id="user_{{ $assignment->id }}">
                                <label class="form-check-label" for="user_{{ $assignment->id }}">
                                     {{ $assignment->name }} ({{ $assignment->email }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to remove the selected users from this task?')">Remove Selected Users</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
