<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import ListComp from "@/Components/Inicio/ListComp.vue";
</script>

<template>
    <AppLayout title="Inicio">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Inicio
            </h2>
        </template>

        <div class="pt-6">
            <div class="max-w-7xl pb-3 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
        
        <div class="pt-6" v-if="$page.props.user.type !== 'admin'">
            <div class="max-w-7xl pb-3 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <ListComp />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
