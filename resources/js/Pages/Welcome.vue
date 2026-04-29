<template>
    <AppLayout>
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Welcome to Dealership
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Laravel 12 + Inertia.js + Vue 3 Authentication System
            </p>
            <div class="space-x-4">
                <Link
                    v-if="!$page.props.auth.user"
                    href="/login"
                    class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded"
                >
                    Get Started
                </Link>
                <Link
                    v-else
                    href="/dashboard"
                    class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded"
                >
                    Go to Dashboard
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
