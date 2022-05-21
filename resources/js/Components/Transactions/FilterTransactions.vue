<template>
    <div class="col-span-12 sm:col-span-5 mb-3">
        <label for="account" class="block text-sm font-medium text-gray-700">
            Filtrar por
        </label>
        <select id="account" name="account"  v-model="selected" class="mt-1 block w-full py-2 px-3 border border-gray-300 text-white bg-slate-500 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option :value="-1">Seleccionar..</option>
            <option v-for="(option, index) in accounts" :key="index" :value="option.type">{{ option.label }}</option>
        </select>
    </div>
    <div class="col-span-12 sm:col-span-4 ml-1 mb-3">
        <label for="value" class="block text-sm font-medium text-gray-700">Filtro</label>
        <input :disabled="selected==-1" @input="$emit('change', { value: $event.target.value, filter: selected})" type="number" name="value" id="value" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
    </div>
</template>
<script>
export default {
    emits: ['change'],
    data(){
        return {
            accounts: [
                {
                    type: 'origin',
                    label: 'Cuenta origen'
                },
                {
                    type: 'destiny',
                    label: 'Cuenta destino'
                }
            ],
            selected: -1,
        }
    },
}
</script>