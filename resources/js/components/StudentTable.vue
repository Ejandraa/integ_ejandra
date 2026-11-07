<script setup>
defineProps({
    students: { type: Array, required: true },
    loading: Boolean,
    canManage: Boolean,
});
defineEmits(['edit', 'delete']);
</script>

<template>
    <p v-if="loading">Loading students...</p>
    <p v-else-if="students.length === 0">No students found.</p>

    <table v-else>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Age</th>
                <th v-if="canManage">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="s in students" :key="s.id">
                <td>{{ s.student_id }}</td>
                <td>{{ s.name }}</td>
                <td>{{ s.email }}</td>
                <td>{{ s.course }}</td>
                <td>{{ s.age }}</td>
                <td v-if="canManage">
                    <button class="btn edit" @click="$emit('edit', s)">Edit</button>
                    <button class="btn danger" @click="$emit('delete', s)">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>