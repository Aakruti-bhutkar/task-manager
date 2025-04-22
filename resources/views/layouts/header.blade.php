<!-- resources/views/layouts/header.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center w-100">
        <a class="navbar-brand" href="#">Task Manager Admin</a>
        <!-- Display logged-in user details -->
        <span class="navbar-text">
            Welcome, {{ Auth::user()->name }}  <!-- Show user name -->
            <!-- Or, you can display email: {{ Auth::user()->email }} -->
        </span>
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>
        @endauth
    </div>
</nav>
