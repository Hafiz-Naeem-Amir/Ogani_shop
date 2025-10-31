<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Welcome to Our Site</h1>
        <p>Click the button below to register</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

       <a href="{{ route('custom.register') }}" class="btn btn-primary btn-lg"> Registration</a>
       <a href="{{ route('custom.login') }}" class="btn btn-primary btn-lg"> Login</a>

    </div>
</body>
</html>
