<script setup>
import { ref, onMounted } from 'vue';
import { api } from './api';
import AlertMessage from './components/AlertMessage.vue';
import StudentForm from './components/StudentForm.vue';
import StudentTable from './components/StudentTable.vue';

const students = ref([]);
const editing = ref(null);
const loading = ref(false);
const saving = ref(false);
const errors = ref({});
const message = ref('');
const messageType = ref('success');

function notify(text, type = 'success') {
    message.value = text;
    messageType.value = type;
    setTimeout(() => (message.value = ''), 4000);
}

async function loadStudents() {
    loading.value = true;
    try {
        const res = await api('/students');
        students.value = res.data;
    } catch (e) {
        notify(e.message, 'error');
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
            notify(e.message, 'error');
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
        notify(e.message, 'error');
    }
}

onMounted(loadStudents);
</script>

<template>
    <main class="container">
        <h1>Student Management</h1>
        <AlertMessage :message="message" :type="messageType" />

        <StudentForm
            :student="editing"
            :errors="errors"
            :saving="saving"
            @save="saveStudent"
            @cancel="editing = null; errors = {}"
        />

        <StudentTable
            :students="students"
            :loading="loading"
            @edit="(s) => (editing = s)"
            @delete="deleteStudent"
        />
    </main>
</template>