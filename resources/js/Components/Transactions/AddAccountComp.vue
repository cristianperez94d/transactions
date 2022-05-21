<template>
    <div class="col-span-12">
        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 text-center">
                <button type="button" @click="add = !add" :class="[add ? 'bg-gray-600 hover:bg-gray-700' : 'bg-indigo-600 hover:bg-indigo-700' ]" class="m-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" >
                    <span v-show="!add">Matricular Cuenta</span>
                    <span v-show="add">Cerrar</span>
                </button>        
            </div>
        </div>
        <hr>
        <form @submit.prevent="submit()" v-show="add" class="col-span-12">
            <div class="grid grid-cols-12 gap-6 mt-3">
                <p class="col-span-12 text-center text-gray-700 text-sm">
                    Ingrese la informacion solicitada para matricular la cuenta
                </p>
                <FormAlert class="col-span-12" :msgs="alerts.msgs" :color="alerts.color" />

                <div v-if="$page.props.user.type === 'admin'" class="col-span-12 sm:col-span-6">
                    <label for="value" class="block text-sm font-medium text-gray-700">Valor</label>
                    <input v-model="this.form.current_value" type="number" name="value" id="value" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>
                
                <div v-else class="col-span-12 sm:col-span-6">
                    <label for="number" class="block text-sm font-medium text-gray-700">Nº Cuenta</label>
                    <input v-model="this.form.account" type="number" name="number" id="number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>
                
                <div class="col-span-12 sm:col-span-6">
                    <label for="identification" class="block text-sm font-medium text-gray-700">Identificacion</label>
                    <input v-model="this.form.identification" type="number" name="identification" id="identification" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>

                <div class="col-span-12 px-4 py-3 text-center">
                    <button type="submit" class="ml-2 mr-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" >
                        Matricular
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import FormAlert from '@/Components/Alerts/FormAlert.vue';
import Accounts from '@/Request/Accounts.js';
export default {
    components:{
        FormAlert,
    },
    data(){
        return {
            add: null,
            loader: null,
            alerts: {
                msgs: null,
                color: 'red',
            },
            form: {
                account: null,
                identification: null,
                current_value: null,
            },
        }
    },
    watch: {
        add(){
            this.form.account = null;
            this.form.identification = null;
            this.form.current_value = null;

            this.alerts.msgs = null;
        },
    },
    methods: {
        async submit(){
            try {
                this.loader = true;
                this.alerts.msgs = null;
                
                let resp = await Accounts.post({ form: this.form});
                if(resp.data.result.state === 'OK' ){
                    const register = resp.data.result.data;
                    this.alerts.msgs = `La cuenta se registro exitosamente con el Nº ${register.account}`;
                    this.alerts.color = "green";

                    this.form.account = null;
                    this.form.identification = null;
                    this.form.current_value = null;
                }
                else if(resp.data.result.state === 'ERROR' ){
                    this.alerts.msgs = resp.data.result.data;
                    this.alerts.color = "red";
                }
            } catch (error) {
                this.alerts.msgs = 'Ocurrio un error en la peticion'
            }
            this.loader = false;
            
        },
    },
    
}
</script>