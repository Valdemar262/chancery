import './bootstrap';

const API_BASE = '/api';
const AUTH_STORAGE_KEY = 'chancery_auth';

function getAuth() {
    try {
        const data = localStorage.getItem(AUTH_STORAGE_KEY);
        return data ? JSON.parse(data) : null;
    } catch {
        return null;
    }
}

function setAuth(auth) {
    localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(auth));
}

function clearAuth() {
    localStorage.removeItem(AUTH_STORAGE_KEY);
}

function getToken() {
    const auth = getAuth();
    return auth?.tokenData?.access_token || auth?.access_token || null;
}

function setupAxiosAuth() {
    const token = getToken();
    if (token) {
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
        delete window.axios.defaults.headers.common['Authorization'];
    }
}

function updateNavForAuth() {
    const auth = getAuth();
    const navUser = document.getElementById('nav-user');
    const logoutBtn = document.getElementById('logout-btn');
    const navLogin = document.getElementById('nav-login');

    if (auth?.user && navUser && logoutBtn && navLogin) {
        navUser.textContent = auth.user.name || auth.user.email;
        navUser.classList.remove('hidden');
        logoutBtn.classList.remove('hidden');
        navLogin.classList.add('hidden');
    } else if (navLogin) {
        navLogin.classList.remove('hidden');
        if (navUser) navUser.classList.add('hidden');
        if (logoutBtn) logoutBtn.classList.add('hidden');
    }
}

function setupStatementActions(listEl) {
    listEl.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action][data-statement-id]');
        if (!btn) return;

        const action = btn.dataset.action;
        const statementId = btn.dataset.statementId;
        if (!statementId || !['approve', 'reject'].includes(action)) return;

        btn.disabled = true;

        try {
            const method = action === 'approve' ? 'approve' : 'reject';
            await window.axios.put(`${API_BASE}/statement/${method}/${statementId}`);

            const card = btn.closest('.rounded-lg');
            const statusEl = card?.querySelector('.statement-status');
            if (statusEl) {
                statusEl.textContent = action === 'approve' ? 'approved' : 'rejected';
                statusEl.className = 'statement-status px-2 py-1 text-xs font-medium rounded-full ' +
                    (action === 'approve' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
            }
            const buttonsContainer = btn.closest('.mt-3');
            buttonsContainer?.remove();
        } catch (err) {
            alert('Ошибка: ' + (err.response?.data?.message || err.message));
        } finally {
            btn.disabled = false;
        }
    });
}

function initEchoAndSubscribe(userId, isAdmin) {
    const token = getToken();
    if (!token || !window.Echo) {
        console.warn('[Echo] Skip: no token or Echo', { hasToken: !!token, hasEcho: !!window.Echo });
        return;
    }

    window.Echo.connector.options.auth.headers.Authorization = `Bearer ${token}`;

    const updateStatementStatus = (e, status, statusClass, removeButtons = true) => {
        const card = document.querySelector(`[data-statement-id="${e.statement_id}"]`);
        const statusEl = card?.querySelector('.statement-status');
        if (statusEl) {
            statusEl.textContent = status;
            statusEl.className = 'statement-status px-2 py-1 text-xs font-medium rounded-full ' + statusClass;
            if (removeButtons) {
                card?.querySelector('.mt-3')?.remove();
            }
        }
    };

    const addAdminButtons = (statementId) => {
        const card = document.querySelector(`[data-statement-id="${statementId}"]`);
        if (!card || !isAdmin || card.querySelector('.mt-3')) return;
        const buttonsHtml = `
            <div class="mt-3 flex gap-2">
                <button type="button" data-action="approve" data-statement-id="${statementId}"
                    class="px-3 py-1.5 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                    Одобрить
                </button>
                <button type="button" data-action="reject" data-statement-id="${statementId}"
                    class="px-3 py-1.5 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50">
                    Отклонить
                </button>
            </div>
        `;
        card.insertAdjacentHTML('beforeend', buttonsHtml);
    };

    const subscribeToChannel = (channelName) => {
        const channel = window.Echo.private(channelName);
        channel.listen('.statement.submitted', (e) => {
            updateStatementStatus(e, e.status || 'submitted', 'bg-yellow-100 text-yellow-800', false);
            addAdminButtons(e.statement_id);
        });
        channel.listen('.statement.approved', (e) => {
            updateStatementStatus(e, e.status || 'approved', 'bg-green-100 text-green-800');
        });
        channel.listen('.statement.rejected', (e) => {
            updateStatementStatus(e, e.status || 'rejected', 'bg-red-100 text-red-800');
        });
        channel.listen('.booking.conflict', (e) => {
            alert('Execute booking conflict create');
        });
    };

    console.log('[Echo] Подписка на канал user.' + userId);
    subscribeToChannel(`user.${userId}`);

    if (isAdmin) {
        console.log('[Echo] Подписка на канал admin');
        subscribeToChannel('admin');
    }

    const connector = window.Echo.connector;
    const pusher = connector?.pusher ?? connector?.options?.pusher;
    if (pusher?.connection) {
        ['connecting', 'connected', 'unavailable', 'failed', 'disconnected', 'error'].forEach((ev) => {
            pusher.connection.bind(ev, (d) => console.log('[Echo]', ev, d));
        });
        pusher.connection.bind('state_change', (s) => console.log('[Echo] state_change:', s?.previous, '->', s?.current));
    } else {
        console.warn('[Echo] Pusher не найден. Connector keys:', connector ? Object.keys(connector) : 'no connector');
    }
}

function renderStatement(statement, isAdmin) {
    const statusColors = {
        draft: 'bg-gray-100 text-gray-800',
        submitted: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
    };
    const statusClass = statusColors[statement.status] || 'bg-gray-100 text-gray-800';

    const showAdminActions = isAdmin && statement.status === 'submitted';

    const adminButtons = showAdminActions
        ? `
            <div class="mt-3 flex gap-2">
                <button type="button" data-action="approve" data-statement-id="${statement.id}"
                    class="px-3 py-1.5 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50">
                    Одобрить
                </button>
                <button type="button" data-action="reject" data-statement-id="${statement.id}"
                    class="px-3 py-1.5 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50">
                    Отклонить
                </button>
            </div>
        `
        : '';

    return `
        <div data-statement-id="${statement.id}" class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-medium text-gray-900">${statement.title}</h3>
                    <p class="text-sm text-gray-500 mt-1">№ ${statement.number} • ${statement.date || '—'}</p>
                </div>
                <span class="statement-status px-2 py-1 text-xs font-medium rounded-full ${statusClass}">${statement.status}</span>
            </div>
            ${adminButtons}
        </div>
    `;
}

function initLoginPage() {
    const form = document.getElementById('login-form');
    const errorEl = document.getElementById('login-error');
    const btn = document.getElementById('login-btn');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorEl.classList.add('hidden');
        btn.disabled = true;

        try {
            const res = await window.axios.post(`${API_BASE}/login`, {
                email: form.email.value,
                password: form.password.value,
            });

            const data = res.data;
            const tokenData = data.tokenData || data;
            const user = data.user;

            if (!tokenData.access_token) {
                throw new Error('Неверный ответ от сервера');
            }

            setAuth({
                user: user || { id: null, name: form.email.value },
                tokenData: tokenData,
                access_token: tokenData.access_token,
            });

            setupAxiosAuth();
            window.location.href = '/statements';
        } catch (err) {
            const msg = err.response?.data?.message || err.response?.data?.error || err.message || 'Ошибка входа';
            errorEl.textContent = typeof msg === 'object' ? JSON.stringify(msg) : msg;
            errorEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
        }
    });
}

async function initStatementsPage() {
    const token = getToken();
    if (!token) {
        window.location.href = '/login';
        return;
    }

    setupAxiosAuth();
    updateNavForAuth();

    const loadingEl = document.getElementById('loading');
    const listEl = document.getElementById('statements-list');
    const emptyEl = document.getElementById('empty-state');

    try {
        const [meRes, statementsRes] = await Promise.all([
            window.axios.get(`${API_BASE}/me`),
            window.axios.get(`${API_BASE}/allStatements`),
        ]);

        const user = meRes.data;
        const userId = user?.id || user?.data?.id;
        const roles = user?.roles || [];
        const isAdmin = roles.includes('admin');
        const statements = statementsRes.data?.allStatements || statementsRes.data?.data?.allStatements || statementsRes.data || [];

        loadingEl.classList.add('hidden');

        if (Array.isArray(statements) && statements.length > 0) {
            listEl.classList.remove('hidden');
            listEl.innerHTML = statements.map((s) => renderStatement(s, isAdmin)).join('');
            if (userId) {
                console.log('[Echo] Инициализация для user', userId, 'isAdmin:', isAdmin);
                initEchoAndSubscribe(userId, isAdmin);
            } else {
                console.warn('[Echo] userId не получен из /me');
            }
            setupStatementActions(listEl);
        } else {
            emptyEl.classList.remove('hidden');
        }
    } catch (err) {
        if (err.response?.status === 401) {
            clearAuth();
            window.location.href = '/login';
            return;
        }
        loadingEl.textContent = 'Ошибка загрузки: ' + (err.response?.data?.message || err.message);
    }

    document.getElementById('logout-btn')?.addEventListener('click', () => {
        clearAuth();
        window.location.href = '/login';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('login-form')) {
        initLoginPage();
    } else if (document.getElementById('statements-list')) {
        initStatementsPage();
    } else {
        updateNavForAuth();
        document.getElementById('logout-btn')?.addEventListener('click', () => {
            clearAuth();
            window.location.href = '/login';
        });
    }
});
