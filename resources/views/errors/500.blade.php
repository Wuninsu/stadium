<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="error-shell">
    <div class="error-card">
        <div class="error-whistle"><i class="bi bi-cone-striped"></i></div>
        <div class="error-code mb-2">5<span class="accent">00</span></div>
        <h3 class="fw-bold mb-2">Half-time, something went wrong.</h3>
        <p class="text-secondary mb-4">Our servers hit an unexpected error while processing your request. Please try again in a moment.</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ route('home') }}" class="btn btn-flood rounded-3 px-4"><i class="bi bi-house me-2"></i>Back to Home</a>
            <a href="{{ route('admin') }}" class="btn btn-outline-pitch rounded-3 px-4"><i class="bi bi-speedometer2 me-2"></i>Go to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
