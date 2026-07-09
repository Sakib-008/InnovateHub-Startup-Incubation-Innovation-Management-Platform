<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'InnovateHub') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="auth-wrapper">
    <div class="container" style="max-width: 460px;">
        <div class="card auth-card">
            <div class="auth-header">
                <h2>InnovateHub</h2>
                <p>Startup Incubation & Innovation Platform</p>
            </div>
            <div class="auth-body">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
</html>