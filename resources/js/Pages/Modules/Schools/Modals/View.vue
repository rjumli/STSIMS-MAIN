<template>
    <b-modal v-model="showModal" hide-footer title="View Campus" class="v-modal-custom" modal-class="zoomIn" fullscreen>
        <template v-slot:header>
            <h5 class="modal-title">{{school.name}}</h5>
            <i @click="showModal=false" class="ri-close-circle-fill float-end" style="cursor:pointer; font-size: 40px; margin-top: -10px; margin-bottom: -20px;"></i>
        </template>
        <div class="hstack gap-3 flex-wrap mt-n3 mb-n2">
             <div class="text-muted"><i class="ri-building-line align-bottom me-1"></i>
                <span class="text-body fw-medium">{{campus.campus}} Campus</span>
            </div>
            <div class="vr"></div>
            <div class="text-muted">Class :
                <span class="text-body fw-medium">{{school.class.name}}</span>
            </div>
            <div class="vr"></div>
            <div class="text-muted">Term :
                <span class="text-body fw-medium">{{campus.term.name}}</span>
            </div>
            <div class="vr"></div>
            <div class="text-muted">Grading :
                <span class="text-body fw-medium">{{campus.grading.name}}</span>
            </div>
            <div class="vr"></div>
            <div class="text-muted"><i class="ri-map-pin-2-fill align-bottom me-1"></i>
                <span class="text-body fw-medium">{{campus.municipality.name}}, {{campus.province.name}}, {{campus.region.region}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"><hr /></div>
            <div class="col-md-4">
                <div class="d-flex flex-column h-100">
                    <!-- <b-list-group>
                        <b-list-group-item><i class="ri-bill-line align-middle me-2"></i> Send the billing agreement<span class="float-end">A</span></b-list-group-item>
                        <b-list-group-item><i class="ri-file-copy-2-line align-middle me-2"></i>Send over all the documentation.</b-list-group-item>
                        <b-list-group-item><i class="ri-question-answer-line align-middle me-2"></i>Meeting with daron to review the intake form</b-list-group-item>
                        <b-list-group-item><i class="ri-secure-payment-line align-middle me-2"></i>Check uikings theme and give customer support</b-list-group-item>
                    </b-list-group>
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium">Class</td>
                                <td class="text-end">{{school.class.name}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Grading System</td>
                                <td class="text-end">{{campus.grading.name}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Term Type</td>
                                <td class="text-end">{{campus.term.type}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Status</td>
                                <td class="text-end">
                                    <span v-if="campus.is_active" class="badge bg-secondary bg-success">Active</span>
                                    <span v-else class="badge bg-secondary bg-danger">Inactive</span>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>
            </div>
            <div class="col-md-8">
                <Count :counts="counts"/>
                <hr class="text-muted mt-0 mb-3"/>
                <div class="d-flex align-items-center mb-3">
                    <span class="flex-grow-1 text-primary fw-semibold fs-14 mb-n2"> LIST OF COURSES</span>
                    <div class="flex-shrink-0">
                        <button @click="addCourse()" class="btn btn-danger btn-sm text-nowrap flex-shrink-0 mb-n1" type="button">
                            <div class="btn-content"><i class="ri-add-circle-fill align-bottom me-1"></i> Add Course</div>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-centered table-nowrap mb-0">
                        <thead class="text-muted table-light fs-10">
                            <tr>
                                <th width="40%" scope="col">Course</th>
                                <th width="10%" class="text-center">Scholars</th>
                                <th width="10%" class="text-center">Certification</th>
                                <th width="10%" class="text-center">Validity</th>
                                <th width="10%" class="text-center">Type</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="10%" class="text-center"></th>
                            </tr>
                        </thead>
                    </table>
                    <SimpleBar v-if="campus.courses" class="align-items-center d-flex justify-content-center" :style="{ height: 'calc(100vh - 500px)' }">
                    <table class="table table-centered table-bordered table-nowrap mb-0">
                        <tbody class="fs-11 ">
                            <tr v-for="(list,index) in campus.courses" v-bind:key="index">
                                <td width="40%" class="fw-medium">{{list.course}}</td>
                                <td width="10%" class="text-center">4</td>
                                <td width="10%" class="text-center">{{list.certification}}</td>
                                <td width="10%" class="text-center">{{list.validity}}</td>
                                <td width="10%" class="text-center">{{list.type}}</td>
                                <td width="10%" class="text-center">{{list.is_active}}</td>
                                <td width="10%" class="text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                    </SimpleBar>
                </div>
            </div>
        </div>
    </b-modal>
    <Course :school_id="campus.id" :courses="courses" ref="course"/>
</template>
<script>
import { SimpleBar } from 'simplebar-vue3';
import Course from '../Modals/Course.vue';
import Count from '../Profile/Count.vue';
export default {
    components: { Count, Course, SimpleBar },
    props: ['school','courses'],
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            campus: {
                municipality:{}, province:{}, region:{}, class:{}, term:{}, grading: {}
            },
            counts: [],
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        show(data){
            this.campus = data;
            this.showModal = true;
        },
        fetch(){
            axios.get(this.currentUrl+'/schools',{
                params : { type: 'counts', school_id: this.school.id}
            })
            .then(response => {
                this.counts = response.data;       
            })
            .catch(err => console.log(err));
        },
        addCourse(){
            this.$refs.course.show();
        },
        update(data){
            this.campus.courses.unshift(data);
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
