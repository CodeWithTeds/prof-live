<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>lonita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-neutral-100 min-h-screen">
  <!-- Mobile sidebar (hidden off-canvas by default) -->
  <div id="mobile-sidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-neutral-900 border-r border-neutral-200 dark:border-neutral-800 transform -translate-x-full transition-transform duration-200 ease-in-out md:hidden">
    <div class="h-16 flex items-center px-4 border-b border-neutral-200 dark:border-neutral-800">
      <span class="text-lg font-semibold">Admin Yarn</span>
      <button id="close-sidebar" class="ml-auto p-2 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Close sidebar">
        <x-lucide-x class="w-4 h-4" />
      </button>
    </div>

    <nav class="p-4">
      <ul class="space-y-1">
        <li>
          <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
            <x-lucide-home class="w-4 h-4" /> Dashboard
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
            <x-lucide-users class="w-4 h-4" /> Users
          </a>
        </li>
        <li>
          <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
            <x-lucide-list-todo class="w-4 h-4" /> Tasks
          </a>
        </li>
      </ul>
    </nav>
  </div>

  <!-- Layout wrapper: desktop two-column, mobile single column -->
  <div class="min-h-screen md:grid md:grid-cols-[16rem,1fr]">
    <!-- Desktop sidebar (hidden on mobile) -->
    <aside class="hidden md:flex md:flex-col w-64 bg-gray-100 dark:bg-gray-900 shadow-lg">
      <nav class="p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800">
          <x-lucide-x class="w-4 h-4" /> dashboard
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800">
          <x-lucide-users class="w-4 h-4" /> Users
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800">
          <x-lucide-list-todo class="w-4 h-4" /> Task
        </a>
      </nav>
    </aside>

    <!-- Main content (always visible) -->
    <div class="flex flex-col min-h-screen">
      <!-- Header -->
      <header class="h-16 sticky top-0 z-40 flex items-center gap-3 border-b border-neutral-200 dark:border-neutral-800 bg-white/80 dark:bg-neutral-900/80 px-4">
        <!-- Mobile menu button (visible only on mobile) -->
        <button id="open-sidebar" class="md:hidden p-2 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors" aria-label="Open sidebar">
          <x-lucide-menu class="w-5 h-5" />
        </button>

        <div class="flex items-center gap-2">
          <span class="text-sm text-neutral-500">Admin</span>
          <span class="text-neutral-400">/</span>
          <h1 class="text-base font-semibold">{{ $title ?? 'Dashboard' }}</h1>
        </div>
      </header>

      <!-- Page body -->
      <main class="flex-1 p-4 md:p-6">
        {{ $slot }}
      </main>
    </div>
  </div>

  <script>
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    openBtn?.addEventListener('click', () => mobileSidebar.classList.remove('-translate-x-full'));
    closeBtn?.addEventListener('click', () => mobileSidebar.classList.add('-translate-x-full'));

  </script>
</body>


</html>
