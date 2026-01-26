<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
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
        .card {
            background: var(--surface-0);
            border: 1px solid var(--border-200);
            border-radius: 1rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
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
        .focus-ring:focus-visible {
            outline: 3px solid rgba(59, 130, 246, 0.35);
            outline-offset: 2px;
        }
    </style>
    <title>App Layout</title>
</head>

<body class="antialiased">

<header class="bg-white border-b border-slate-200">
    <div class="container-app py-5 flex items-center justify-between">
        <div class="flex items-center gap-3 text-xl font-semibold text-slate-900">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">M</span>
            MediBook
        </div>

        <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
            <a href="/" class="focus-ring hover:text-blue-700 transition">Home</a>
            <a href="/appointments" class="focus-ring hover:text-blue-700 transition">Appointments</a>
            <a href="/cabinets" class="focus-ring hover:text-blue-700 transition">Doctors</a>
            <a href="#" class="focus-ring hover:text-blue-700 transition">Contact</a>
        </nav>

        <button class="md:hidden btn btn-secondary focus-ring">Menu</button>
    </div>
</header>

<main class="py-10">
    <div class="container-app">
        <div class="card p-6">
            {{ $slot }}
        </div>
    </div>
</main>

<footer class="mt-12 bg-white border-t border-slate-200">
    <div class="container-app py-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between text-sm text-slate-500">
        <p>© 2025 MediBook — All Rights Reserved.</p>
        <div class="flex items-center gap-4">
            <a href="#" class="focus-ring hover:text-blue-700 transition">Privacy Policy</a>
            <a href="#" class="focus-ring hover:text-blue-700 transition">Terms of Service</a>
            <a href="#" class="focus-ring hover:text-blue-700 transition">Help</a>
        </div>
    </div>
</footer>

@livewireScripts
</body>
</html>
