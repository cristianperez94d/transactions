<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Bienvenido" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:justify-center sm:pt-0">
                <img src="@/Assets/wallet.png" alt="logo">
            </div>
            <div class="flex justify-center pt-8 sm:justify-center sm:pt-0">
                <h1 class="text-center dark:text-white text-4xl">Web Transacciones</h1>
            </div>

            <div v-if="canLogin" class="sm:block">
                <Link v-if="$page.props.user" :href="route('inicio')" class="m-2 w-full flex items-center justify-center px-6 py-2 border border-transparent text-small rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Inicio
                </Link>

                <template v-else>
                    <div class="mt-5 sm:justify-center">
                        <Link :href="route('login')" class="m-2 w-full flex items-center justify-center px-6 py-2 border border-transparent text-small rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Iniciar sesión
                        </Link>

                        <Link v-if="canRegister" :href="route('register')" class="m-2 w-full flex items-center justify-center px-6 py-2 border border-transparent text-small rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                            Registro
                        </Link>
                    </div>
                </template>
            </div>

            <div class="flex justify-center mt-4 sm:items-center">
                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--tw-text-opacity));
        }
    }
</style>
