<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'InnovateHub') }}</title>
    <script>
    // Apply theme before CSS renders to prevent flash
    (function() {
        const t = localStorage.getItem('ih-theme') ||
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', t);
    })();
</script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="auth-wrapper">
    {{-- Floating orbs --}}
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <div class="logo-icon">⚡</div>
            </div>
            <h2>InnovateHub</h2>
            <p>Startup Incubation & Innovation Platform</p>
        </div>
        <div class="auth-body">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>