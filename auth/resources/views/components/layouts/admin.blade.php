<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-white text-neutral-900 dark:bg-neutral-950 dark:text-neutral-100 min-h-screen">
    <div class="relative">
        <input id="sidebar-toggle" type="checkbox" class="peer hidden" aria-hidden="true" />

        <x-admin.sidebar />

        <label for="sidebar-toggle" class="fixed inset-0 z-30 bg-black/40 opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto lg:hidden" aria-label="Close sidebar"></label>

        <header class="sticky top-0 z-20 flex items-center gap-3 h-14 px-3 lg:px-4 border-b border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950">
            <label for="sidebar-toggle" class="lg:hidden inline-flex items-center px-3 h-9 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100" aria-label="Open sidebar" role="button">
                Menu
            </label>
            <span class="font-semibold">Admin</span>
        </header>

        <main class="min-h-[calc(100vh-3.5rem)] lg:ml-64 p-4">
            {{ $slot ?? '' }}
        </main>
    </div>
</body>

</html>
