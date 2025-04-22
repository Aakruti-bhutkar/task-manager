@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Task Management System</h2>

    {{-- Stat Cards (Only for Admin) --}}
    @if(Auth::user()->hasRole('admin'))
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Tasks</h5>
                        <h2>{{ isset($totalTasks) ? $totalTasks : 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pending Tasks</h5>
                        <h2>{{ $pendingTasks ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">In Progress Tasks</h5>
                        <h2>{{ $inProgressTasks ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Completed Tasks</h5>
                        <h2>{{ $completedTasks ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- User Performance & Overdue (Only for Admin) --}}
    @if(Auth::user()->hasRole('admin'))
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">User Performance</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Completed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userPerformance as $performance)
                <tr>
                    <td>{{ $performance['name'] }}</td>
                    <td>{{ $performance['completed_count'] }}</td>
                </tr>
            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Overdue Tasks</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($overdueTasks as $task)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Employee's Own Tasks (Only for Employee) --}}
    @if(Auth::user()->hasRole('employee'))
        <div class="row g-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">Your Tasks</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ ucfirst($task->status) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@section('scripts')
    {{-- Add custom scripts for charts or other functionalities --}}
@endsection
