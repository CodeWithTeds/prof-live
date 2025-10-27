/* API client for Tasks CRUD - segregation of concerns */

const BASE = '/api/tasks';

const unwrap = (res) => res?.data?.data ?? res?.data ?? {};

export async function listTasks({ per_page = 50 } = {}) {
    const res = await window.axios.get(BASE, { params: { per_page } });
    const payload = unwrap(res);
    return Array.isArray(payload?.data) ? payload.data : payload; // handle paginator or plain array
}

export async function getByStatus(status) {
    const res = await window.axios.get(`${BASE}/status/${encodeURIComponent(status)}`);
    return unwrap(res);
}

export async function getByPriority(priority) {
    const res = await window.axios.get(`${BASE}/priority/${encodeURIComponent(priority)}`);
    return unwrap(res);
}

export async function createTask(data) {
    const res = await window.axios.post(BASE, data);
    return unwrap(res);
}

export async function updateTask(id, data) {
    const res = await window.axios.patch(`${BASE}/${id}`, data);
    return unwrap(res);
}

export async function deleteTask(id) {
    const res = await window.axios.delete(`${BASE}/${id}`);
    return unwrap(res);
}

export const STATUS_OPTIONS = ['pending', 'in_process', 'completed'];
export const PRIORITY_OPTIONS = ['low', 'medium', 'high'];