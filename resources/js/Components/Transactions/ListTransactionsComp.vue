<template>
    <div class="col-span-12">
        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 text-center">
                <button type="button" @click="consult()" :class="[add ? 'bg-gray-600 hover:bg-gray-700' : 'bg-indigo-600 hover:bg-indigo-700' ]" class="m-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" >
                    <span v-show="!add">Consultar transacciones</span>
                    <span v-show="add">Cerrar</span>
                </button>        
            </div>
        </div>
        <hr>
        <div v-if="add" class="grid grid-cols-12 mt-3">
            <FormAlert :msgs="errors"/>
            <FilterTransactions @change="filter"/>
            <div class=" col-span-12 relative flex flex-col min-w-0 break-words w-full shadow-lg rounded 
            bg-slate-500 text-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1 ">
                            <h3 class="font-semibold text-lg text-white">Lista de transacciones</h3>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto ">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr>
                        <th class="px-6 align-middle border border-solid py-3 text-right text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold bg-slate-500 text-slate-100 border-slate-500">Monto</th>
                        <th class="px-6 align-middle border border-solid py-3 text-right text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold bg-slate-500 text-slate-100 border-slate-500">Cuenta origen</th>
                        <th class="px-6 align-middle border border-solid py-3 text-right text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold bg-slate-500 text-slate-100 border-slate-500">cuenta Destino</th>
                        <th class="px-6 align-middle border border-solid py-3 text-right text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold bg-slate-500 text-slate-100 border-slate-500">Fecha - Hora</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr v-if="loader">
                            <th class="border-t-0 px-6  text-right align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <span>Cargando...</span>
                            </th>
                        </tr>
                        <tr v-for="(transaction , index) in transactions" :key="index">
                            <th class="border-t-0 px-6  text-right align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <span class="ml-3 font-bold text-white">$ {{ mFormatCurrency(transaction.value) }}</span></th>
                            <td class="border-t-0 px-6  text-right align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <span>{{ transaction.origin_id }}</span>
                            </td>
                            <td class="border-t-0 px-6  text-right align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <span>{{ transaction.destiny_id }}</span>
                            </td>
                            <td class="border-t-0 px-6  text-right align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <span>{{ transaction.created_at }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <PaginatedComp 
                @first="first"
                @prev="prev"
                @next="next"
                @last="last"
                @page="page"
                :data="pagination"
            />
        </div>
    </div>
</template>

<script>
import FormAlert from '@/Components/Alerts/FormAlert.vue';
import PaginatedComp from '@/Components/PaginatedComp.vue';
import FilterTransactions from '@/Components/Transactions/FilterTransactions.vue';
import TransactionsReq from '@/Request/Transactions.js';
export default {
    components: {
        FormAlert,
        PaginatedComp,
        FilterTransactions,
    },
    data(){
        return {
            add: null,
            loader: null,
            errors: null,
            transactions: [],
            pagination: {},
            model: null
        }
    },
    methods: {
        consult(){
            this.add = !this.add;
            if(this.add){
                this.submit(1);
            }
        },
        filter(event){
            if(event.filter != -1){
                this.submit(1, event.filter, event.value);
            }
        },
        async submit(page = 1, filter='', value=''){
            try {
                this.loader = true;
                this.errors = null;
                this.transactions = [];
                let resp = await TransactionsReq.get({page, filter, value});
                if(resp.data.result.state === 'OK' ){
                    this.transactions = resp.data.result.data.data;
                    this.pagination = {
                        currentPage: resp.data.result.data.current_page,
                        lastPage: resp.data.result.data.last_page,
                        total: resp.data.result.data.total,
                    }
                    if(this.transactions.length == 0){
                        this.transactions = [{ value: 'No existen registros...'}];
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
        first(page) {
            this.pagination.currentPage = page;
            this.submit(page);
        },
        prev(page) {
            this.pagination.currentPage = page;
            this.submit(page);
        },
        next(page) {
            this.pagination.currentPage = page;
            this.submit(page);
        },
        last(page) {
            this.pagination.currentPage = page;
            this.submit(page);
        },
        page(page) {
            this.pagination.currentPage = page;
            this.submit(page);
        },
    }
}
</script>