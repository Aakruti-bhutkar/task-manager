<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
        #sidebar-wrapper {
            width: 250px;
        }
        .content-wrapper {
            flex: 1;
            padding: 20px;
        }
    </style>
    @stack('scripts')
</head>
<body>
    @include('layouts.header')

    <div class="wrapper">
        @include('layouts.sidebar')

        <div class="content-wrapper">
            @yield('content')
            @if ($errors->any())
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div class="toast align-items-center text-white bg-danger border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ $errors->first() }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('error'))
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

        </div>
    </div>

    @include('layouts.footer')
</body>
</html>
