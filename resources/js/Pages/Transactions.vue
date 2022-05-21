<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import TypeAccountComp from '@/Components/Transactions/TypeAccountComp.vue';
import ListAccountComp from '@/Components/Transactions/ListAccountComp.vue';
import AddAccountComp from '@/Components/Transactions/AddAccountComp.vue';
import ListTransactionsComp from '@/Components/Transactions/ListTransactionsComp.vue';

</script>

<template>
    <AppLayout title="Transacciones bancarias">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Transacciones bancarias
            </h2>
        </template>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <TypeAccountComp />
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <AddAccountComp />
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <ListTransactionsComp />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
