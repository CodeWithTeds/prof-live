// Admin Tasks Page Module - separation of concerns
// - API client: fetch/create/update tasks
// - UI: render rows, bind events, auto-save on edit

const apiBase = '/api/tasks';

function formatDate(value) {
  if (!value) return '';
  const d = new Date(value);
  if (isNaN(d)) return value; // assume YYYY-MM-DD already
  const yyyy = d.getFullYear();
  const mm = String(d.getMonth() + 1).padStart(2, '0');
  const dd = String(d.getDate()).padStart(2, '0');
  return `${yyyy}-${mm}-${dd}`;
}

const TaskAPI = {
  async list({ status, priority } = {}) {
    let url = apiBase;
    if (status) url = `/api/tasks/status/${encodeURIComponent(status)}`;
    else if (priority) url = `/api/tasks/priority/${encodeURIComponent(priority)}`;
    else url = `${apiBase}?per_page=100`;
    const res = await fetch(url);
    const json = await res.json();
    const items = Array.isArray(json.data) ? json.data : (json.data && json.data.data) || [];
    return items;
  },
  async create(payload) {
    const res = await fetch(apiBase, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!res.ok || json.success === false) throw new Error(json.message || 'Failed to create');
    return json.data;
  },
  async update(id, payload) {
    const res = await fetch(`${apiBase}/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!res.ok || json.success === false) throw new Error(json.message || 'Failed to save');
    return json.data;
  },
};

function debounce(fn, delay = 600) {
  let t;
  return (...args) => {
    clearTimeout(t);
    t = setTimeout(() => fn(...args), delay);
  };
}

function setEditable(tr, enabled) {
  const fields = tr.querySelectorAll('[data-field]');
  fields.forEach((el) => (el.disabled = !enabled));
  tr.classList.toggle('is-editing', enabled);
}

function attachRowBehavior(tr) {
  const title = tr.querySelector('[data-field="title"]');
  const status = tr.querySelector('[data-field="status"]');
  const priority = tr.querySelector('[data-field="priority"]');
  const due = tr.querySelector('[data-field="due_date"]');
  const statusEl = tr.querySelector('[data-el="status"]');
  const editBtn = tr.querySelector('[data-action="edit"]');

  const saveNow = async () => {
    if (!tr.dataset.id) return;
    statusEl.textContent = 'Saving...';
    const payload = {
      title: title.value,
      status: status.value,
      priority: priority.value,
      due_date: due.value || null,
    };
    try {
      await TaskAPI.update(tr.dataset.id, payload);
      statusEl.textContent = 'Saved';
      setEditable(tr, false);
      setTimeout(() => (statusEl.textContent = ''), 1500);
    } catch (err) {
      statusEl.textContent = err.message || 'Save failed';
    }
  };

  const triggerSave = debounce(saveNow, 600);
  const markEditing = () => {
    statusEl.textContent = 'Edit in action!';
  };

  editBtn.addEventListener('click', () => {
    const nowEditing = !tr.classList.contains('is-editing');
    setEditable(tr, nowEditing);
    if (nowEditing) statusEl.textContent = 'Edit in action!';
    else statusEl.textContent = '';
  });

  title.addEventListener('input', () => {
    markEditing();
    triggerSave();
  });
  title.addEventListener('change', () => {
    markEditing();
    triggerSave();
  });
  title.addEventListener('blur', triggerSave);
  status.addEventListener('change', () => {
    markEditing();
    triggerSave();
  });
  priority.addEventListener('change', () => {
    markEditing();
    triggerSave();
  });
  due.addEventListener('change', () => {
    markEditing();
    triggerSave();
  });
  due.addEventListener('blur', triggerSave);
}

function renderRow(task) {
  const rowTemplate = document.getElementById('task-row-template');
  const rowsTbody = document.getElementById('task-rows');
  const frag = rowTemplate.content.cloneNode(true);
  const tr = frag.querySelector('tr');
  tr.dataset.id = task.id;

  const title = frag.querySelector('[data-field="title"]');
  title.value = task.title || '';
  const status = frag.querySelector('[data-field="status"]');
  status.value = task.status || 'pending';
  const priority = frag.querySelector('[data-field="priority"]');
  priority.value = task.priority || 'low';
  const due = frag.querySelector('[data-field="due_date"]');
  due.value = formatDate(task.due_date);

  attachRowBehavior(tr);
  rowsTbody.appendChild(frag);
}

async function refreshTasks() {
  const rowsTbody = document.getElementById('task-rows');
  const filterStatus = document.getElementById('filter-status');
  const filterPriority = document.getElementById('filter-priority');
  rowsTbody.innerHTML = '';
  try {
    const items = await TaskAPI.list({ status: filterStatus.value || undefined, priority: filterPriority.value || undefined });
    items.forEach(renderRow);
  } catch (err) {
    console.error('Failed to fetch tasks', err);
  }
}

function bindCreateForm() {
  const createForm = document.getElementById('task-create-form');
  const createStatus = document.getElementById('task-create-status');
  if (!createForm) return;
  createForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    createStatus.textContent = 'Creating...';
    const fd = new FormData(createForm);
    const payload = Object.fromEntries(fd.entries());
    if (!payload.due_date) payload.due_date = null;
    try {
      const created = await TaskAPI.create(payload);
      renderRow(created);
      createForm.reset();
      createStatus.textContent = 'Created';
      setTimeout(() => (createStatus.textContent = ''), 1500);
    } catch (err) {
      createStatus.textContent = err.message || 'Create failed';
    }
  });
}

function bindFilters() {
  const refreshBtn = document.getElementById('refresh-tasks');
  const filterStatus = document.getElementById('filter-status');
  const filterPriority = document.getElementById('filter-priority');
  refreshBtn && refreshBtn.addEventListener('click', refreshTasks);
  filterStatus && filterStatus.addEventListener('change', refreshTasks);
  filterPriority && filterPriority.addEventListener('change', refreshTasks);
}

document.addEventListener('DOMContentLoaded', () => {
  bindCreateForm();
  bindFilters();
  refreshTasks();
});
