<script setup>
import { ref } from 'vue';

defineProps({
    loading: Boolean,
    errors: { type: Object, default: () => ({}) },
});
const emit = defineEmits(['login']);

const email = ref('');
const password = ref('');

function submit() {
    emit('login', { email: email.value, password: password.value });
}
</script>

<template>
    <form class="card" @submit.prevent="submit">
        <h2>Login</h2>
        <label>Email
            <input v-model="email" type="email" autocomplete="username" />
            <small v-if="errors.email" class="err">{{ errors.email[0] }}</small>
        </label>
        <label>Password
            <input v-model="password" type="password" autocomplete="current-password" />
            <small v-if="errors.password" class="err">{{ errors.password[0] }}</small>
        </label>
        <button class="btn primary" type="submit" :disabled="loading">
            {{ loading ? 'Logging in...' : 'Login' }}
        </button>
    </form>
</template>