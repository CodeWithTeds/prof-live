<x-layouts.admin :title="'Tasks'">
    <div id="tasks-app" class="space-y-6">
        <!-- Create Task -->
        <section class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4">Create Task</h2>
            <form id="task-create-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}" />
                <div>
                    <label class="block text-sm mb-1">Title</label>
                    <input name="title" type="text" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2" required />
                </div>
                <div>
                    <label class="block text-sm mb-1">Due Date</label>
                    <input name="due_date" type="date" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm mb-1">Status</label>
                    <select name="status" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Priority</label>
                    <select name="priority" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="px-4 py-2 rounded-md bg-neutral-900 text-white dark:bg-neutral-100 dark:text-neutral-900">Create</button>
                    <div id="task-create-status" class="text-sm text-neutral-600 dark:text-neutral-400"></div>
                </div>
            </form>
        </section>

        <!-- Filters -->
        <section class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-lg p-4">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-sm mb-1">Filter by Status</label>
                    <select id="filter-status" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Filter by Priority</label>
                    <select id="filter-priority" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-transparent p-2">
                        <option value="">All</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <button id="refresh-tasks" class="ml-auto px-4 py-2 rounded-md bg-neutral-200 dark:bg-neutral-800">Refresh</button>
            </div>
        </section>

        <!-- Tasks Table -->
        <section class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-neutral-50 dark:bg-neutral-800">
                        <tr>
                            <th class="text-left px-4 py-2">Title</th>
                            <th class="text-left px-4 py-2">Status</th>
                            <th class="text-left px-4 py-2">Priority</th>
                            <th class="text-left px-4 py-2">Due Date</th>
                            <th class="text-left px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="task-rows"></tbody>
                </table>
            </div>
        </section>

        <!-- Row template -->
        <template id="task-row-template">
            <tr class="border-t border-neutral-200 dark:border-neutral-800">
                <td class="px-4 py-2">
                    <input data-field="title" class="w-full bg-transparent border border-transparent focus:border-neutral-300 dark:focus:border-neutral-700 rounded p-1" disabled />
                </td>
                <td class="px-4 py-2">
                    <select data-field="status" class="w-full bg-transparent border border-neutral-300 dark:border-neutral-700 rounded p-1" disabled>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </td>
                <td class="px-4 py-2">
                    <select data-field="priority" class="w-full bg-transparent border border-neutral-300 dark:border-neutral-700 rounded p-1" disabled>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </td>
                <td class="px-4 py-2">
                    <input data-field="due_date" type="date" class="w-full bg-transparent border border-neutral-300 dark:border-neutral-700 rounded p-1" disabled />
                </td>
                <td class="px-4 py-2">
                    <div class="flex items-center gap-2">
                        <button data-action="edit" class="px-3 py-1 rounded bg-neutral-200 dark:bg-neutral-800">Edit</button>
                        <span class="text-xs text-neutral-500" data-el="status"></span>
                    </div>
                </td>
            </tr>
        </template>
    </div>

    @vite('resources/js/admin/tasks-index.js')
</x-layouts.admin>