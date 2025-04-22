@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Assign Users to Task</h5>
            <a href="{{ route('task-assignment.index') }}" class="btn btn-sm btn-light">← Back to Task List</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Assignment Form -->
            <form method="POST" action="{{ route('task-assignment.store', $task->id) }}">
                @csrf

                <!-- Task Title -->
                <div class="mb-3">
                    <label class="form-label">Task Title</label>
                    <input type="text" class="form-control" value="{{ $task->title }}" readonly>
                </div>

                <!-- User List -->
                <div class="mb-3">
                    <label for="user_ids" class="form-label">Select Users</label>
                    <select name="user_ids[]" id="user_ids" class="form-select" multiple required size="3">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, $assignedUserIds ?? []) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Assign Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
