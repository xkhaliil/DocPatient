<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS & Alpine.js via CDN -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.1/dist/cdn.min.js"></script>
        @livewireStyles
        <style>
            :root {
                --brand-50: #eff6ff;
                --brand-200: #bfdbfe;
                --brand-500: #3b82f6;
                --brand-600: #2563eb;
                --brand-700: #1d4ed8;
                --ink-900: #0f172a;
                --ink-500: #64748b;
                --surface-0: #ffffff;
                --surface-100: #f1f5f9;
                --border-200: #e2e8f0;
            }
            body {
                font-family: "Figtree", system-ui, -apple-system, "Segoe UI", sans-serif;
                background: var(--surface-100);
                color: var(--ink-900);
            }
            .card {
                background: var(--surface-0);
                border: 1px solid var(--border-200);
                border-radius: 1rem;
                box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
            }
            .focus-ring:focus-visible {
                outline: 3px solid rgba(59, 130, 246, 0.35);
                outline-offset: 2px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <x-breeze.application-logo class="w-20 h-20 fill-current text-blue-600" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 card">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
</html>
