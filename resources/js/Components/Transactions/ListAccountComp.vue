<template>
    <div  v-if="accounts.length > 0" class="col-span-12 sm:col-span-6">
        <label for="account" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <select id="account" name="account" @change="$emit('change', $event.target.value)" v-model="selected" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option :value="null">Seleccionar..</option>
            <option v-for="(option, index) in accounts" :key="index" :value="option.account">Cuenta Nº {{ option.account }}</option>
        </select>
    </div>
</template>
<script>
export default {
    emits: ['change'],
    props: {
        accounts: Array,
        label: String,
    },
    data(){
        return {
            selected: null,
        }
    },
    computed: {
        currentValue(){
            const a = this.accounts.find(el => el.id == this.selected);
            if(a) return this.mFormatCurrency(a.current_value);
            return 0;
        },
    },
    methods: {
        mFormatCurrency(currency = "", isInput = true) {
            if (typeof currency !== "string" && typeof currency !== "number") return currency;
            let newCurrency = String(currency).replaceAll(",", "");
            if (Number(newCurrency)) {
            if (newCurrency.split(".").length - 1 == 1)
                return new Intl.NumberFormat("en-EN", {
                    minimumFractionDigits: 0,
                }).format(newCurrency);
            else if (isInput) {
                return new Intl.NumberFormat("en-EN", {
                    minimumFractionDigits: 0,
                }).format(newCurrency);
            }
            return new Intl.NumberFormat("en-EN", {
                minimumFractionDigits: 0,
            }).format(newCurrency);
            } else {
                return currency;
            }
        },
    }
}
</script>