<script setup>
import { ref, onMounted } from 'vue';
import { api } from './api';
import { useAuth } from './composables/useAuth';
import AlertMessage from './components/AlertMessage.vue';
import LoginForm from './components/LoginForm.vue';
import StudentForm from './components/StudentForm.vue';
import StudentTable from './components/StudentTable.vue';

const { state, isLoggedIn, isAdmin, login, logout, restore, clear } = useAuth();

const students = ref([]);
const editing = ref(null);
const loading = ref(false);
const saving = ref(false);
const loggingIn = ref(false);
const errors = ref({});
const loginErrors = ref({});
const message = ref('');
const messageType = ref('success');

function notify(text, type = 'success') {
    message.value = text;
    messageType.value = type;
    setTimeout(() => (message.value = ''), 4000);
}

// One place to deal with API failures
function handleError(e) {
    if (e.status === 401) {
        clear();
        students.value = [];
        notify('Session expired. Please log in again.', 'error');
    } else if (e.status === 403) {
        notify('Forbidden: only an Administrator can do that.', 'error');
    } else {
        notify(e.message, 'error');
    }
}

async function handleLogin(credentials) {
    loginErrors.value = {};
    loggingIn.value = true;
    try {
        await login(credentials.email, credentials.password);
        notify(`Welcome, ${state.user.name}!`);
        await loadStudents();
    } catch (e) {
        if (e.status === 422) loginErrors.value = e.errors;
        else notify(e.message, 'error');
    } finally {
        loggingIn.value = false;
    }
}

async function handleLogout() {
    await logout();
    students.value = [];
    editing.value = null;
    notify('Logged out.', 'info');
}

async function loadStudents() {
    loading.value = true;
    try {
        const res = await api('/students');
        students.value = res.data;
    } catch (e) {
        handleError(e);
    } finally {
        loading.value = false;
    }
}

async function saveStudent(form) {
    errors.value = {};
    saving.value = true;
    try {
        const res = editing.value
            ? await api(`/students/${editing.value.id}`, { method: 'PUT', body: form })
            : await api('/students', { method: 'POST', body: form });
        notify(res.message);
        editing.value = null;
        await loadStudents();
    } catch (e) {
        if (e.status === 422) {
            errors.value = e.errors;
            notify('Please fix the validation errors.', 'error');
        } else {
            handleError(e);
        }
    } finally {
        saving.value = false;
    }
}

async function deleteStudent(student) {
    if (!confirm(`Delete ${student.name}?`)) return;
    try {
        const res = await api(`/students/${student.id}`, { method: 'DELETE' });
        notify(res.message);
        await loadStudents();
    } catch (e) {
        handleError(e);
    }
}

onMounted(async () => {
    await restore();
    if (isLoggedIn.value) await loadStudents();
});
</script>

<template>
    <main class="container">
        <h1>Student Management</h1>
        <AlertMessage :message="message" :type="messageType" />

        <LoginForm v-if="!isLoggedIn" :loading="loggingIn" :errors="loginErrors" @login="handleLogin" />

        <template v-else>
            <div class="card row" style="justify-content: space-between">
                <span>
                    Logged in as <strong>{{ state.user.name }}</strong>
                    ({{ isAdmin ? 'Administrator' : 'Regular User' }})
                </span>
                <button class="btn" @click="handleLogout">Logout</button>
            </div>

            <StudentForm
                v-if="isAdmin"
                :student="editing"
                :errors="errors"
                :saving="saving"
                @save="saveStudent"
                @cancel="editing = null; errors = {}"
            />
            <p v-else class="alert info">Read-only access: only Administrators can add, edit, or delete students.</p>

            <StudentTable
                :students="students"
                :loading="loading"
                :can-manage="isAdmin"
                @edit="(s) => (editing = s)"
                @delete="deleteStudent"
            />
        </template>
    </main>
</template>