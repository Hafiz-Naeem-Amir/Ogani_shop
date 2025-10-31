<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .btn-primary-custom {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary-custom:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 w-100" style="max-width: 400px;">

            @can('customer')
                <h3 class="text-center mb-4">Customer</h3>
            @endcan

            @can('admin')
                <h3 class="text-center mb-4">Admin</h3>
            @endcan

            @can('editor')
                <h3 class="text-center mb-4">Editor</h3>
            @endcan

        </div>
    </div>
</body>
</html>
