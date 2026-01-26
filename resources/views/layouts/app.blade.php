<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS & Alpine.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.1/dist/cdn.min.js"></script>
    @livewireStyles
    <style>
        :root {
            --brand-50: #eff6ff;
            --brand-100: #dbeafe;
            --brand-200: #bfdbfe;
            --brand-500: #3b82f6;
            --brand-600: #2563eb;
            --brand-700: #1d4ed8;
            --brand-800: #1e40af;
            --accent-500: #0ea5a4;
            --success-600: #16a34a;
            --warning-600: #d97706;
            --danger-600: #dc2626;
            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --surface-0: #ffffff;
            --surface-50: #f8fafc;
            --surface-100: #f1f5f9;
            --border-200: #e2e8f0;
        }
        body {
            font-family: "Figtree", system-ui, -apple-system, "Segoe UI", sans-serif;
            background: var(--surface-100);
            color: var(--ink-900);
        }
        .container-app {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }
        /* Navigation Shell */
        .nav-shell {
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--border-200);
            backdrop-filter: blur(10px);
        }
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.75rem;
            padding: 0.75rem 0;
            flex-wrap: wrap;
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            flex-wrap: wrap;
        }
        .nav-logo-link {
            display: inline-flex;
            align-items: center;
            border-radius: 0.75rem;
        }
        /* Navigation Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--ink-700);
            padding: 0.4rem 0;
            line-height: 1.2;
            transition: color 150ms ease;
        }
        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 100%;
            height: 2px;
            background: var(--brand-600);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 150ms ease;
        }
        .nav-link:hover {
            color: var(--brand-700);
        }
        .nav-link:hover::after {
            transform: scaleX(1);
        }
        .nav-link-active {
            color: var(--ink-900);
        }
        .nav-link-active::after {
            transform: scaleX(1);
        }
        /* User Menu */
        .nav-user {
            display: flex;
            align-items: center;
        }
        .nav-user-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--ink-700);
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 150ms ease, background 150ms ease, border-color 150ms ease;
        }
        .nav-user-trigger:hover {
            color: var(--brand-700);
            background: var(--brand-50);
            border-color: var(--brand-100);
        }
        .nav-user-icon {
            width: 1rem;
            height: 1rem;
        }
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .nav-inner {
                align-items: flex-start;
            }
            .nav-left {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            .nav-links {
                width: 100%;
                gap: 1rem;
            }
            .nav-user {
                width: 100%;
                justify-content: flex-start;
            }
            .nav-user-trigger {
                width: 100%;
                justify-content: space-between;
            }
        }
        .card {
            background: var(--surface-0);
            border: 1px solid var(--border-200);
            border-radius: 1rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
        }
        .card-soft {
            background: var(--surface-50);
            border: 1px solid var(--border-200);
            border-radius: 1rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            border-radius: 0.75rem;
            padding: 0.6rem 1rem;
            transition: all 150ms ease;
        }
        .btn-primary {
            background: var(--brand-600);
            color: #fff;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.24);
        }
        .btn-primary:hover {
            background: var(--brand-700);
        }
        .btn-secondary {
            background: #fff;
            color: var(--ink-900);
            border: 1px solid var(--border-200);
        }
        .btn-secondary:hover {
            border-color: var(--brand-200);
            color: var(--brand-700);
        }
        .btn-ghost {
            color: var(--brand-700);
        }
        .btn-ghost:hover {
            background: var(--brand-50);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.6rem;
            border-radius: 999px;
        }
        .badge-info {
            background: var(--brand-50);
            color: var(--brand-700);
        }
        .badge-success {
            background: #dcfce7;
            color: var(--success-600);
        }
        .badge-warning {
            background: #ffedd5;
            color: var(--warning-600);
        }
        .badge-danger {
            background: #fee2e2;
            color: var(--danger-600);
        }
        .input {
            width: 100%;
            border: 1px solid var(--border-200);
            border-radius: 0.75rem;
            padding: 0.6rem 0.8rem;
            background: #fff;
            transition: all 150ms ease;
        }
        .input:focus {
            outline: none;
            border-color: var(--brand-500);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        .table-shell {
            border: 1px solid var(--border-200);
            border-radius: 1rem;
            overflow: hidden;
            background: #fff;
        }
        .table-head {
            background: var(--surface-50);
            color: var(--ink-700);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .focus-ring:focus-visible {
            outline: 3px solid rgba(59, 130, 246, 0.35);
            outline-offset: 2px;
        }
    </style>
</head>
<body class="antialiased">
<div class="min-h-screen">
    @include('layouts.app_navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white border-b border-slate-200">
            <div class="container-app py-6">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        <div class="py-10">
            <div class="container-app">
                <div class="card">
                    <div class="p-6 text-slate-900">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@livewireScripts
</body>
</html>
