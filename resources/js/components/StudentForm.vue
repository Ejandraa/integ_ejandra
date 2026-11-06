<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    student: { type: Object, default: null },
    errors: { type: Object, default: () => ({}) },
    saving: Boolean,
});
const emit = defineEmits(['save', 'cancel']);

const empty = () => ({ student_id: '', name: '', email: '', course: '', age: '' });
const form = reactive(empty());

watch(
    () => props.student,
    (s) => Object.assign(form, s ? { ...s } : empty()),
    { immediate: true },
);

function submit() {
    emit('save', {
        student_id: form.student_id,
        name: form.name,
        email: form.email,
        course: form.course,
        age: form.age,
    });
}
</script>

<template>
    <form class="card" @submit.prevent="submit">
        <h2>{{ student ? 'Edit Student' : 'Add Student' }}</h2>

        <label>Student ID
            <input v-model="form.student_id" type="number" />
            <small v-if="errors.student_id" class="err">{{ errors.student_id[0] }}</small>
        </label>
        <label>Name
            <input v-model="form.name" type="text" />
            <small v-if="errors.name" class="err">{{ errors.name[0] }}</small>
        </label>
        <label>Email
            <input v-model="form.email" type="email" />
            <small v-if="errors.email" class="err">{{ errors.email[0] }}</small>
        </label>
        <label>Course
            <input v-model="form.course" type="text" />
            <small v-if="errors.course" class="err">{{ errors.course[0] }}</small>
        </label>
        <label>Age
            <input v-model="form.age" type="number" />
            <small v-if="errors.age" class="err">{{ errors.age[0] }}</small>
        </label>

        <div class="row">
            <button class="btn primary" type="submit" :disabled="saving">
                {{ saving ? 'Saving...' : student ? 'Update' : 'Add' }}
            </button>
            <button v-if="student" class="btn" type="button" @click="emit('cancel')">Cancel</button>
        </div>
    </form>
</template>