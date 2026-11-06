export function getToken() {
    return localStorage.getItem('token');
}

export async function api(path, { method = 'GET', body } = {}) {
    const headers = { Accept: 'application/json' };
    if (body) headers['Content-Type'] = 'application/json';

    const token = getToken();
    if (token) headers.Authorization = `Bearer ${token}`;

    const res = await fetch(`/api${path}`, {
        method,
        headers,
        body: body ? JSON.stringify(body) : undefined,
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
        const err = new Error(data.message || 'Request failed');
        err.status = res.status;
        err.errors = data.errors || {};
        throw err;
    }
    return data;
}