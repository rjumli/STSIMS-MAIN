<template>
<Head :title="school.data.name" />
    <Headr :school="school"/>
    <b-row>
       <Sidebar :id="school.data.id"/>
       <b-col lg="8">
            <b-row>
                <b-col lg="12">
                    <b-card no-body>
                        <b-card-header class="align-items-center d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <ul class="nav justify-content-start nav-tabs-custom rounded card-header-tabs border-bottom-0"
                                    role="tablist">
                                    <li class="nav-item">
                                        <b-link class="nav-link active" data-bs-toggle="tab" href="#campuses" role="tab">
                                            Campuses
                                        </b-link>
                                    </li>
                                </ul>
                            </div>
                             <div class="flex-grow-1 oveflow-hidden">
                                <p class="text-muted text-end xtext-truncates mb-0">Schools</p>
                            </div>
                        </b-card-header>
                        <b-card-body style="height: calc(100vh - 340px)">
                            <div class="tab-content text-muted">
                                <div class="tab-pane active" id="campuses" role="tabpanel">
                                    <b-col lg>
                                        <div class="input-group mb-3 mt-n1">
                                            <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                            <input type="text" v-model="keyword" placeholder="Search Campus" class="form-control" style="width: 30%;">
                                            <b-button type="button" variant="primary" @click="create()">
                                                <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                                            </b-button>
                                        </div>
                                    </b-col>
                                    <div class="table-responsive mb-3">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <thead class="table-light fs-12">
                                                <tr>
                                                    <th width="25%">Campus</th>
                                                    <th width="10%" class="text-center">Is Main</th>
                                                    <th width="15%" class="text-center">Term</th>
                                                    <th width="15%" class="text-center">Grading</th>
                                                    <th width="15%" class="text-center">Assigned</th>
                                                    <th width="10%" class="text-center">Status</th>
                                                    <th width="10%" class="text-end"></th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <SimpleBar class="align-items-center d-flex justify-content-center" :style="{ height: 'calc(100vh - 500px)' }">
                                        <table class="table align-middle table-nowrap mb-0">
                                            <tbody class="list form-check-all">
                                                <tr v-for="list in school.data.campuses" v-bind:key="list.id" :class="[(list.is_active == 0) ? 'table-warnings' : '']">
                                                    <td width="25%" v-b-tooltip.hover :title="list.oldname">
                                                        <h5 class="fs-13 mb-0 text-dark">{{list.campus}}, {{list.municipality.name}}</h5>
                                                        <p class="fs-12 text-muted mb-0">{{list.province.name}}, {{list.region.region}}</p>
                                                    </td>
                                                    <td width="10%" class="text-center">
                                                        <span v-if="list.is_main" class="badge bg-success">Yes</span>
                                                        <span v-else class="badge bg-danger">No</span>
                                                    </td>
                                                    <td width="15%" class="text-center">{{(list.term) ? list.term.name : ''}}</td>
                                                    <td width="15%" class="text-center">{{(list.grading) ? list.grading.name : ''}}</td>
                                                    <td width="15%" class="text-center">{{(list.assigned) ? list.assigned.region : ''}}</td>
                                                    <td width="10%" class="text-center">
                                                        <span v-if="list.is_active" class="badge bg-success">Active</span>
                                                        <span v-else class="badge bg-danger">Inactive</span>
                                                    </td>
                                                    <td width="10%" class="text-end">
                                                        <b-button variant="soft-primary" v-b-tooltip.hover title="Edit" size="sm" class="edit-list me-1"><i class="ri-pencil-fill align-bottom"></i> </b-button>
                                                        <b-button @click="show(list)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </SimpleBar>
                                        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                                    </div>      
                                </div>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
            </b-row>
       </b-col>
    </b-row>
    <Campus :terms="terms" :gradings="gradings" :regions="regions" ref="campus"/>
    <View :courses="courses" :school="school.data" ref="view"/>
</template>
<script>
import Headr from './Headr.vue';
import Sidebar from './Sidebar.vue';
import { SimpleBar } from 'simplebar-vue3';
import Campus from '../Modals/Campus.vue';
import View from '../Modals/View.vue';
export default {
    components: { Headr, Sidebar, SimpleBar, Campus, View },
    props: ['school','dropdowns','regions', 'courses'],
    data(){
        return {
            status: ''
        }
    },
    computed: {
        terms : function() {
            return this.dropdowns.filter(x => x.classification === "Term Type");
        },
        classes : function() {
            return this.dropdowns.filter(x => x.classification === "Class");
        },
        gradings : function() {
            return this.dropdowns.filter(x => x.classification === "Grading System");
        },
        data() {
            return this.$page.props.flash.data;
        }
    },
    watch: {
        data: {
            deep: true,
            handler(val = null) {
                if(val != null){
                    if(this.status == 'campus'){
                        this.add(val.data);
                    }else{
                        console.log(val.data);
                        this.$refs.view.update(val.data);
                        // let index = this.school.data.campuses.findIndex(school => school.id === val.data.school_id);
                        // this.school.campuses[index].course.unshift(val.data);
                    }
                }
            },
        }
    },
    methods: {
        create(){
            this.status = 'campus';
            this.$refs.campus.show(this.school.data.id);
        },
        add(data){
            this.school.data.campuses.unshift(data);
        },
        show(data){
            this.status = 'course';
            this.$refs.view.show(data);
        },
    }
}
</script>
