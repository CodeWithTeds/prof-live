import { listTasks, getByStatus, getByPriority, createTask, updateTask, deleteTask, STATUS_OPTIONS, PRIORITY_OPTIONS } from '../api/tasks';

function qs(sel, root = document) { return root.querySelector(sel); }
function qsa(sel, root = document) { return Array.from(root.querySelectorAll(sel)); }

function formatDate(value) {
    if (!value) return '';
    try {
        const d = new Date(value);
        const iso = d.toISOString().slice(0, 10);
        return iso;
    } catch { return ''; }
}

async function loadTasks({ status = '', priority = '' } = {}) {
    try {
        let items;
        if (status) {
            items = await getByStatus(status);
        } else if (priority) {
            items = await getByPriority(priority);
        } else {
            items = await listTasks({ per_page: 50 });
        }
        renderRows(items?.data ?? items);
    } catch (err) {
        console.error('Failed to load tasks', err);
    }
}

function renderRows(tasks) {
    const tbody = qs('#task-rows');
    const tpl = qs('#task-row-template');
    tbody.innerHTML = '';

    (tasks || []).forEach(task => {
        const row = tpl.content.cloneNode(true);
        const tr = row.querySelector('tr');
        tr.dataset.id = task.id;

        const titleEl = tr.querySelector('[data-field="title"]');
        const statusEl = tr.querySelector('[data-field="status"]');
        const priorityEl = tr.querySelector('[data-field="priority"]');
        const dueEl = tr.querySelector('[data-field="due_date"]');
        const msgEl = tr.querySelector('[data-el="status"]');

        titleEl.value = task.title ?? '';
        statusEl.value = task.status ?? STATUS_OPTIONS[0];
        priorityEl.value = task.priority ?? PRIORITY_OPTIONS[0];
        dueEl.value = formatDate(task.due_date);

        tr.querySelector('[data-action="save"]').addEventListener('click', async () => {
            msgEl.textContent = 'Saving…';
            try {
                const payload = {
                    title: titleEl.value,
                    status: statusEl.value,
                    priority: priorityEl.value,
                    due_date: dueEl.value || null,
                };
                const updated = await updateTask(task.id, payload);
                msgEl.textContent = 'Saved';
            } catch (e) {
                console.error(e);
                msgEl.textContent = 'Error saving';
            } finally {
                setTimeout(() => (msgEl.textContent = ''), 1500);
            }
        });

        tr.querySelector('[data-action="delete"]').addEventListener('click', async () => {
            if (!confirm('Delete this task?')) return;
            msgEl.textContent = 'Deleting…';
            try {
                await deleteTask(task.id);
                tr.remove();
            } catch (e) {
                console.error(e);
                msgEl.textContent = 'Error deleting';
                setTimeout(() => (msgEl.textContent = ''), 1500);
            }
        });

        tbody.appendChild(row);
    });
}

function bindCreateForm() {
    const form = qs('#task-create-form');
    const statusEl = qs('#task-create-status');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        statusEl.textContent = 'Creating…';
        const fd = new FormData(form);
        const data = Object.fromEntries(fd.entries());
        try {
            const created = await createTask(data);
            statusEl.textContent = 'Created';
            form.reset();
            await loadTasks();
        } catch (err) {
            console.error(err);
            statusEl.textContent = 'Error creating task';
        } finally {
            setTimeout(() => (statusEl.textContent = ''), 1500);
        }
    });
}

function bindFilters() {
    const statusSel = qs('#filter-status');
    const prioritySel = qs('#filter-priority');
    const refreshBtn = qs('#refresh-tasks');

    const refresh = () => loadTasks({ status: statusSel.value, priority: prioritySel.value });

    statusSel?.addEventListener('change', refresh);
    prioritySel?.addEventListener('change', refresh);
    refreshBtn?.addEventListener('click', refresh);
}

function init() {
    const root = qs('#tasks-app');
    if (!root) return;

    bindCreateForm();
    bindFilters();
    loadTasks();
}

document.addEventListener('DOMContentLoaded', init);