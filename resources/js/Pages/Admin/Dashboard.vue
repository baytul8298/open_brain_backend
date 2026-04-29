<template>
    <AppLayout>
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
            <p class="text-gray-700 mb-4">
                Welcome back, <strong>{{ $page.props.auth.user.name }}</strong>!
            </p>
            <p class="text-gray-600">
                You're logged in. This is your dashboard.
            </p>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
