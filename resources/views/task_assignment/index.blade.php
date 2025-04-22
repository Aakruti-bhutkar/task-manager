@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Assigned Task List</h2>
    <!-- <a href="{{ route('task-assignment.create') }}" class="btn btn-success mb-3">+ Assign Task</a> -->

    <!-- Task Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Assigned Users</th>
                <th>Assigned By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taskAssignment as $data)
            <tr>
                <td>{{$data->id}}</td>
                <td>{{$data->title}}</td>
                <td>@foreach ($data->assignees as $assignee) {{$assignee->name}}, @endforeach</td>
                
                <td>{{ $data->taskAssignedBy->assignedBy->name ?? '-' }}</td>

                <td>
                    <a href="{{ route('task-assignment.add', $data->id) }}" class="btn btn-sm btn-primary">Assign User</a>
                    <a href="{{ route('task-assignment.remove', $data->id) }}" class="btn btn-sm btn-danger">Remove User</a>
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
