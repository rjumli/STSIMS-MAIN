<template>
    <div class="p-4 d-flex flex-column h-100">
        <div class="mt-auto">
            <b-row class="g-1">
            <b-col lg="4">
                <button @click="upload()" class="btn btn-soft-primary btn-sm w-100" type="button">
                    <div class="btn-content"> Import </div>
                </button>
            </b-col>
            <b-col lg="4">
                <button @click="truncate()" class="btn btn-soft-primary btn-sm w-100" type="button">
                    <div class="btn-content"> Truncate </div>
                </button>
            </b-col>
            <b-col lg="4">
                <button @click="bank()" class="btn btn-primary btn-sm w-100" type="button">
                    <div class="btn-content"> Update</div>
                </button>
            </b-col>
        </b-row>
        </div>
    </div>
    <Import ref="upload" @info="refresh()"/>
    <Truncate ref="truncate" @info="refresh()"/>
</template>
<script>
import Import from './Modals/Import.vue';
import Truncate from './Modals/Truncate.vue';
export default {
    components: { Import, Truncate },
    data(){
        return {
            currentUrl: window.location.origin,
            statistics: { statuses: [], types: [], active:[], sync: '' },
            options: ['Ongoing Scholars','Graduated Scholars','Total Scholars'],
            options2: ['Undegraduate Scholarhip','Junior Level Science Scholarship'],
            colors: ['text-primary','text-info','text-success'],
            type: ''
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(){
            axios.get(this.currentUrl + '/scholars', {
                params: { type: 'statistics'}
            })
            .then(response => {
                this.statistics = response.data;
            })
            .catch(err => console.log(err));
        },
        upload(){
            this.$refs.upload.show();
        },
        truncate(){
            this.$refs.truncate.show();
        },
        bank(){
            this.$refs.bank.show();
        },
        refresh(){
            this.fetch();
            this.$emit('info',true);
        },
    }
}
</script>