<template>
    <div class="col-span-12">
        <div class="grid grid-cols-12 gap-6">
            <FormAlert :msgs="errors" class="pb-3" />
            <div class="col-span-12 relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded 
            bg-slate-500 text-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1 ">
                            <h3 class="font-semibold text-lg text-white">Lista de cuentas de usuarios</h3>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto ">
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead>
                        <tr>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">N Cuenta</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">Valor actual</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">Tipo</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">Estado</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr v-if="loader">
                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                                    <span>Cargando...</span>
                                </th>
                            </tr>
                            <tr v-for="(account , index) in accounts" :key="index">
                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                                    <span class="ml-3 font-bold text-white">{{ account.account }}</span></th>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    <span v-if="account.type == 'propia'">{{ mFormatCurrency(account.current_value) }}</span>
                                    <span v-else>No aplica</span>
                                </td>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ account.type }}
                                </td>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ account.state }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FormAlert from '@/Components/Alerts/FormAlert.vue';
import AccountReq from '@/Request/Accounts.js';
export default {
    components: {
        FormAlert,
    },
    data(){
        return {
            loader: null,
            errors: null,
            accounts: []
        }
    },
    created(){
        this.submit();
    },
    methods: {
        async submit(){
            try {
                this.loader = true;
                this.errors = null;
                this.accounts = [];
                let resp = await AccountReq.get();
                if(resp.data.result.state === 'OK' ){
                    this.accounts = resp.data.result.data;
                    if(this.accounts.length == 0){
                       this.accounts = [{ name: 'No existen registros...'}]
                    }
                }
                else if(resp.data.result.state === 'ERROR' ){
                    this.errors = resp.data.result.data;
                }
            } catch (error) {
                this.errors = 'Ocurrio un error en la peticion'
            }
            this.loader = false;
            
        },
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