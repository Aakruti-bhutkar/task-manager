
<div class="bg-light border-right" id="sidebar-wrapper">
    <!-- Sidebar Menu -->
    <div class="list-group list-group-flush">
        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-light">Dashboard</a>
        <a href="{{ route('tasks.index') }}" class="list-group-item list-group-item-action bg-light">Tasks</a>

        <!-- Display "Task Assignment" only if the user is an Admin -->
        @if(Auth::user()->hasRole('admin'))
            
            <a href="{{ route('task-assignment.index') }}" class="list-group-item list-group-item-action bg-light">Task Assignments</a>
        @endif

    </div>
</div>
