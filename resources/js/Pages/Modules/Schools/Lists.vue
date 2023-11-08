<template>
    <b-row class="g-2 mb-2 mt-n1">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="keyword" placeholder="Search School" class="form-control" style="width: 30%;">
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="ri-refresh-fill text-primary search-icon"></i>
                </span>
                <span @click="showImport" class="input-group-text" v-b-tooltip.hover title="Import" style="cursor: pointer;"> 
                    <i class="ri-file-excel-2-fill text-primary search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="create()">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 45%;">Name</th>
                    <th style="width: 15%;" class="text-center">Campus</th>
                    <th style="width: 15%;" class="text-center">Class</th>
                    <th style="width: 15%;" class="text-center">Status</th>
                    <th style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="index" :class="[(list.is_active == 0) ? 'table-warnings' : '']">
                    <td>
                        <div class="avatar-xs chat-user-img online">
                            <span v-if="list.avatar == 'school.jpg'" :class="'avatar-title rounded-circle bg-primary text-white'">{{list.name[0]}}</span>
                            <img v-else :src="currentUrl+'/images/avatars/'+list.avatar" alt="" class="avatar-xs rounded-circle">
                            <span v-if="list.is_active" class="user-status text-success"></span>
                        </div>
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.shortcut}}</p>
                    </td>
                    <td class="text-center">{{list.campuses.length}} {{(list.campuses.length > 1) ? 'campuses' : 'campus'}}</td>
                    <td class="text-center">{{list.class.name}}</td>
                    <td class="text-center">
                        <span v-if="list.is_active" class="badge bg-success">Active</span>
                        <span v-else class="badge bg-danger">Inactive</span>
                    </td>
                    <td class="text-end">
                        <b-button @click="edit(list,index)" variant="soft-primary" v-b-tooltip.hover title="Edit" size="sm" class="edit-list me-1">
                            <i class="ri-pencil-fill align-bottom"></i>
                        </b-button>
                        <Link :href="`/schools/${list.code}`"><b-button variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button></Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create :classes="classes" ref="create"/>
    <Import ref="import"/>
</template>
<script>
import Import from './Modals/Import.vue';
import Create from './Modals/Create.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components : { Pagination, Create, Import },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists : [],
            meta: {},
            links: {},
            index: '',
            keyword: '',
        }
    },
    computed: {
        data() {
            return this.$page.props.flash.data;
        },
        classes : function() {
            return this.dropdowns.filter(x => x.classification === "Class");
        },
    },
    watch: {
        data: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    switch(this.type){
                        case 'create':
                            this.fetch();
                        break;
                        case 'edit':
                            this.lists[this.index] = val.data;
                        break;
                    }
                }
            },
        },
        keyword(newVal){
            this.checkSearchStr(newVal)
        },
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/schools';
            axios.get(page_url,{
                params : {
                    keyword : this.keyword,
                    counts: ((window.innerHeight-350)/56),
                    type: 'lists'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        create(){
            this.type = 'create';
            this.$refs.create.show();
        },
        showImport(){
            this.$refs.import.show();
        },
        edit(data,index){
            this.type = 'edit';
            this.index = index;
            this.$refs.create.edit(data);
        },
        refresh(){
            this.keyword = '';
            this.fetch();
        }
    }
}
</script>