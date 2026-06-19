<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'InnovateHub') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
    <div class="container" style="max-width: 480px;">
        <h2 class="text-center mb-4 fw-bold text-primary">InnovateHub</h2>
        @yield('content')
    </div>
</body>
</html>