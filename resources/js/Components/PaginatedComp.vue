<template>
    <nav class="mt-3 col-span-12 text-center">
        <ul class="inline-flex flex-wrap">
            <li>
                <button :disabled="!prevPage" :class="!prevPage ? 'bg-slate-400' : 'hover:text-slate-700 bg-slate-500 hover:bg-slate-700'" @click="first" class="py-1 px-3 hover:text-white text-white ml-0 rounded-l-lg">Primero</button>
            </li>
            <li>
                <button :disabled="!prevPage" :class="!prevPage ? 'bg-slate-400' : 'hover:text-slate-700 bg-slate-500 hover:bg-slate-700'" @click="prev" class="py-1 px-3 hover:text-white text-white ml-0">Anterior</button>
            </li>
            <li  v-for="numPage of numPages" :key="numPage.index">
                <button :disabled="dataPaginate.currentPage === numPage" :class="dataPaginate.currentPage === numPage ? 'bg-slate-700' : 'hover:text-slate-700 bg-slate-500 hover:bg-slate-700' " :title="`Página ${numPage}`" @click="page(numPage)" class="py-1 px-3 hover:text-white text-white">{{ numPage }}</button>
            </li>
            <li>
                <button :disabled="!nextPage" :class="!nextPage ? 'bg-slate-400' : 'hover:text-slate-700 bg-slate-500 hover:bg-slate-700'" @click="next" class="py-1 px-3 hover:text-white text-white">Siguiente</button>
            </li>
            <li>
                <button :disabled="!nextPage" :class="!nextPage ? 'bg-slate-400' : 'hover:text-slate-700 bg-slate-500 hover:bg-slate-700'" @click="last" class="py-1 px-3 hover:text-white text-white rounded-r-lg">Ultimo</button>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    props: {
        data: {
            type: Object,
            default: () => {
                return {
                currentPage: null,
                lastPage: null,
                total: null,
                };
            },
        },
    },
    data() {
        return {
        dataPaginate: {
            currentPage: null,
            lastPage: null,
            total: null,
        },
        offset: 2,
        };
    },
    computed: {
        prevPage() {
        return this.dataPaginate.currentPage
            ? this.dataPaginate.currentPage - 1
            : 0;
        },
        nextPage() {
        return this.dataPaginate.currentPage &&
            this.dataPaginate.lastPage > this.dataPaginate.currentPage
            ? this.dataPaginate.currentPage + 1
            : 0;
        },
        numPages() {
        if (!this.dataPaginate.total) {
            return [];
        }
        let from = this.dataPaginate.currentPage - this.offset;
        if (from < 1) {
            from = 1;
        }
        let to = from + this.offset * 2;
        if (to >= this.dataPaginate.lastPage) {
            to = this.dataPaginate.lastPage;
        }
        let pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }
        return pagesArray;
        },
    },
    methods: {
        page(numPage) {
            this.dataPaginate.currentPage = numPage;
            this.$emit("page", this.dataPaginate.currentPage);
        },
        first() {
            this.dataPaginate.currentPage = 1;
            this.$emit("first", this.dataPaginate.currentPage);
        },
        prev() {
            this.dataPaginate.currentPage <= 1
                ? 1
                : (this.dataPaginate.currentPage -= 1);
            if(this.prevPage){
                this.$emit("prev", this.dataPaginate.currentPage);
            }
        },
        next() {
            this.dataPaginate.currentPage >= this.dataPaginate.lastPage
                ? this.dataPaginate.lastPage
                : (this.dataPaginate.currentPage += 1);
            this.$emit("next", this.dataPaginate.currentPage);
        },
        last() {
            this.dataPaginate.currentPage = this.dataPaginate.lastPage;
            this.$emit("last", this.dataPaginate.currentPage);
        },
    },
    watch: {
        data() {
            this.dataPaginate.currentPage = this.data.currentPage;
            this.dataPaginate.lastPage = this.data.lastPage;
            this.dataPaginate.total = this.data.total;
        },
    },    
}
</script>