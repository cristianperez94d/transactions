<template>
    <FormAlert :msgs="errors" class="pb-3" />
    <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded 
    bg-slate-500 text-white">
        <div class="rounded-t mb-0 px-4 py-3 border-0">
            <div class="flex flex-wrap items-center">
                <div class="relative w-full px-4 max-w-full flex-grow flex-1 ">
                    <h3 class="font-semibold text-lg text-white">Lista de usuarios del sitema</h3>
                </div>
            </div>
        </div>
        <div class="block w-full overflow-x-auto ">
        <table class="items-center w-full bg-transparent border-collapse">
            <thead>
            <tr>
                <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">Nombre</th>
                <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">Identificacion</th>
                <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-slate-500 text-slate-100 border-slate-500">Email</th>
            </tr>
            </thead>

            <tbody>
                <tr v-if="loader">
                    <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                        <span>Cargando...</span>
                    </th>
                </tr>
                <tr v-for="(user , index) in users" :key="index">
                    <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                        <span class="ml-3 font-bold text-white">{{ user.name }}</span></th>
                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                        <span>{{ user.identification }}</span>
                    </td>
                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                        {{ user.email }}
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>

<script>
import FormAlert from '@/Components/Alerts/FormAlert.vue';
import UsersReq from '@/Request/Users.js';
export default {
    components: {
        FormAlert,
    },
    data(){
        return {
            loader: null,
            errors: null,
            users: []
        }
    },
    created(){
        this.submit();
    },
    methods: {
        async submit(type){
            try {
                this.loader = true;
                this.errors = null;
                this.users = [];
                let resp = await UsersReq.get();
                if(resp.data.result.state === 'OK' ){
                    this.users = resp.data.result.data;
                    if(this.users.length == 0){
                       this.users = [{ name: 'No existen registros...'}]
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
    }
}
</script>