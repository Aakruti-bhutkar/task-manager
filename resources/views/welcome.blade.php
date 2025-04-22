<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }

        .home-card {
            max-width: 500px;
            width: 100%;
        }

        .btn-group-custom {
            gap: 1rem;
        }
    </style>
</head>
<body>

    <div class="card p-5 text-center shadow-sm home-card">
        <h1 class="mb-4 text-primary">Task Management System</h1>
        <p class="mb-4 text-muted">Efficiently manage tasks, assign work, and track progress.</p>

        <div class="d-flex justify-content-center btn-group-custom">
            <a href="{{ route('login') }}" class="btn btn-outline-primary px-4">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary px-4">Register</a>
        </div>
    </div>

</body>
</html>
