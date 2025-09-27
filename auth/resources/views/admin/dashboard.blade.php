<x-layouts.admin :title="'Dashboad'">
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <x-lucide-user class="w-4 h-4 text-green-500" />
                Lonita User
            </div>
            <div class="mt-2 text-2xl font-semibold">65,000</div>
            <div class="mt-1 text-xs text-green-600">+100 during lonita rally</div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                Active Task
            </div>
            <div class="mt-2 text-2xl font-semibold">25</div>
            <div class="mt-1 text-xs text-green-600">lonita</div>
        </div>


        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <x-lucide-skull class="w-4 h-4 text-green-500" />
                na tiktokan sa lonita
            </div>
            <div class="mt-2 text-2xl font-semibold">25</div>
            <div class="mt-1 text-xs text-red-600">+100 during lonita rally</div>
        </div>
    </div>

    <div class="mt-6 grid gap-6md md:grid-cols-2">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-4">
            <div class="flex items-center justify-between">
                <x-lucide-user class="w-4 h-4 text-green-500" />
                <h2>Recent Users</h2>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <ul></ul>
        </div>
    </div>
</x-layouts.admin>
