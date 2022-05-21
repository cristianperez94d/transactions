<template>
    <form class="col-span-12">
        <div class="grid grid-cols-12 gap-6">
            <p class="col-span-12 text-center text-gray-700 text-sm">Seleccione el tipo de cuenta para transferrir</p>
            <FormAlert :msgs="errors"/>
        
            <div class="col-span-12 px-4 py-3 text-center">
                <button id="propia" type="button" @click="submit('propia')" class="ml-2 mr-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" :disabled="loader">
                    Cuentas Propias
                </button>
                <button id="terceros" type="button" @click="submit('terceros')" class="ml-2 mr-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" :disabled="loader">
                    Cuentas de terceros
                </button>
            </div>
        </div>
    </form>        
    <hr>
    <form @submit.prevent="submitTrans()" v-if="accounts.length > 0 && !errors" class="col-span-12">
        <div class="grid grid-cols-12 gap-6 mt-3">
            <p class="col-span-12 text-center text-gray-700 text-sm">Seleccione la cuenta origen y destino</p>
            <FormAlert :msgs="alertTransaction.state" :color="alertTransaction.color" />
            
            <ListAccountComp label="Cuentas origen" :accounts="accounts" @change="emitOrigin" />
            <ListAccountComp :label="labelType" :accounts="accountsDestiny" @change="emitDestiny" />
            <div class="col-span-12 sm:col-span-6">
                <label for="value" class="block text-sm font-medium text-gray-700">Monto</label>
                <input type="number" v-model="this.formTrans.value" name="value" id="value" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
            </div>

            <div class="col-span-12 sm:col-span-6">
                <label for="description" class="block text-sm font-medium text-gray-700"> Descripcion </label>
                <div class="mt-1">
                  <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" v-model="this.formTrans.description" />
                </div>
            </div>

            <div class="col-span-12 px-4 py-3 text-center">
                <button type="submit" class="ml-2 mr-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" :disabled="loader">
                    Transferir
                </button>
            </div>
        </div>
    </form>
    <FormAlert :msgs="succesTransaccion" color="green" />
</template>
<script>
import FormAlert from '@/Components/Alerts/FormAlert.vue';
import ListAccountComp from "@/Components/Transactions/ListAccountComp";
import ReqAccounts from '@/Request/Accounts.js';
import ReqTransactions from '@/Request/Transactions.js';

export default {
    emits: ['change'],
    components: {
        FormAlert,
        ListAccountComp,
    },
    data(){
        return {
            loader: null,
            errors: null,
            alertTransaction: {
                state: null,
                color: 'red'
            },
            type: null,
            succesTransaccion: null,
            accounts: [],
            accountsDestiny: [],
            formTrans: {
                origin_id: null,
                destiny_id: null,
                value: null,
                description: null,
            },
            selec: null,
        }
    },
    computed: {
        labelType(){
            let label = 'Cuenta de destino';
            if(this.type == 'terceros'){
                label =`${label} de terceros`; 
            }
            return label;
        },
    },
    methods: {
        async submit(type){
            try {
                this.loader = true;
                this.errors = null;

                this.formTrans.origin_id = null;
                this.formTrans.destiny_id = null;
                this.formTrans.value = null;
                this.formTrans.description = null;
                this.accounts = [];
                this.accountsDestiny = [];

                this.type = type;
                let resp = await ReqAccounts.postType({ form:{ type: 'propia' } });
                let respDestiny = await ReqAccounts.postType({ form:{ type } });
                if(resp.data.result.state === 'OK' ){
                    this.accounts = resp.data.result.data;
                    this.accountsDestiny = respDestiny.data.result.data;
                    if(this.accounts.length == 0){
                       this.errors = "No dispone de una cuenta, por lo que no es posible hacer transferencias";
                    }
                    if(this.accounts.length == 1 && type == 'propia'){
                       this.errors = "Solo dispone de una cuenta y no es posible hacer transferencias entre la misma cuenta";
                    }
                    if(type == 'terceros'){
                        if(this.accountsDestiny.length == 0){
                            this.errors = "No dispone de una cuenta de terceros, por lo que no es posible hacer transferencias";
                        }
                    }
                }
                else if(resp.data.result.state === 'ERROR' ){
                    this.errors = resp.data.result.data;
                }
            } catch (error) {
                this.errors = 'Ocurrio un error en la peticion'
            }
            this.loader = false;
            this.succesTransaccion = null;
            this.alertTransaction.state = null;
            
        },
        async submitTrans(){
            try {
                this.alertTransaction.state = null;
                let resp = await ReqTransactions.post({form : this.formTrans});
                if(resp.data.result.state === 'OK' ){
                    this.succesTransaccion = "Transaccion exitosa. Codigo de transaccion Nº "+resp.data.result.data.id;
                    this.formTrans.origin_id = null;
                    this.formTrans.destiny_id = null;
                    this.formTrans.value = null;
                    this.formTrans.description = null;

                    this.accounts= [];
                    this.accountsDestiny= [];
                }
                else if(resp.data.result.state === 'ERROR' ){
                    this.alertTransaction.color = 'red';
                    this.alertTransaction.state = resp.data.result.data;
                }
            } catch (error) {
                this.alertTransaction.color = 'red'
                this.alertTransaction.state = 'Ocurrio un error en la peticion'
            }
            
        },
        emitOrigin(dataEmit){
            this.formTrans.origin_id = dataEmit;
        },
        emitDestiny(dataEmit){
            this.formTrans.destiny_id = dataEmit;
        },
    }
    
}
</script>