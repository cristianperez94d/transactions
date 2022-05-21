<script setup>
const props = defineProps({
    msgs: null,
    color: {
        type: String,
        default: 'red'
    },
});
</script>

<template>
    <div class="col-span-12" v-if="msgs">
        <div :class="`bg-${color}-100 border border-${color}-300 text-${color}-600 px-4 py-3 rounded relative`" role="alert">
            <span v-if="typeof msgs === `string`" class="block sm:inline">{{ msgs }}</span>
            <ul v-if="typeof msgs === `object` " :class="`list-disc list-inside text-sm text-${color}-600`">
                <li v-for="(error, key) in msgs" :key="key">
                    {{ error }}
                </li>
            </ul>
        </div>        
    </div>
</template>