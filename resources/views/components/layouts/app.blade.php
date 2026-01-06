<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'LAVIADE' }} - Fashion Streetwear</title>
    <meta name="description" content="{{ $description ?? 'LAVIADE - Platform E-Commerce Fashion Streetwear Modern' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-bg: #ffffff;
            --color-surface: #f5f5f5;
            --color-text: #1a1a1a;
            --color-text-secondary: #666666;
            --color-border: #e5e5e5;
            --color-accent: #c0c0c0;
            --color-cta: #1a1a1a;
            --color-cta-text: #ffffff;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .btn-primary {
            background-color: var(--color-cta);
            color: var(--color-cta-text);
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--color-surface);
            color: var(--color-text);
            border: 1px solid var(--color-border);
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: var(--color-border);
        }

        .card {
            background-color: var(--color-surface);
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: var(--color-text);
            font-weight: 500;
            position: relative;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--color-text);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .text-secondary {
            color: var(--color-text-secondary);
        }

        .bg-surface {
            background-color: var(--color-surface);
        }

        .border-custom {
            border-color: var(--color-border);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-surface);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-accent);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-text-secondary);
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased">
    <!-- Navbar -->
    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    @stack('scripts')
</body>

</html>