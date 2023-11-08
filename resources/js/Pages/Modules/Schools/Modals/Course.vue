<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" title="Add Course" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <b-form class="customform mb-2">
            <div class="row customerform">
                <div class="col-md-12 mt-3 mb-2">
                    <div class="form-group">
                        <label>Course: <span v-if="form.errors" v-text="form.errors.course_id" class="haveerror"></span></label>
                            <multiselect 
                            v-model="course" 
                            :options="courses"
                            :allow-empty="false"
                            :show-labels="false"
                            label="name" track-by="id"
                            placeholder="Select Course">
                        </multiselect>
                    </div>
                </div>
                <div class="col-md-12"><hr class="text-muted"/></div>
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label>Certification: <span v-if="form.errors" v-text="form.errors.certification" class="haveerror"></span></label>
                            <multiselect 
                            v-model="certification" 
                            :options="certifications"
                            :allow-empty="false"
                            :show-labels="false"
                            placeholder="Select Certification">
                        </multiselect>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label>Validity: <span v-if="form.errors" v-text="form.errors.validity" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="validity" >
                    </div>
                </div>
                 <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label>Years: <span v-if="form.errors" v-text="form.errors.years" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="years" >
                    </div>
                </div>
                 <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label>Type: <span v-if="form.errors" v-text="form.errors.type" class="haveerror"></span></label>
                        <input type="text" class="form-control" value="n/a" readonly>
                    </div>
                </div>
            </div>
        </b-form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="create('ok')" variant="primary" :disabled="form.processing" block>Save</b-button>
        </template>
    </b-modal>  
</template>
<script>
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    props: ['courses','school_id'],
    components : { Multiselect },
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            form: {},
            certifications: ['COE','COD','FAAP Level III'],
            id: '',
            course: '',
            validity: '',
            years: '',
            certification: '',
            editable: false
        }
    },
    methods : {
        show(){
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.id,
                validity: this.validity,
                certification: this.certification,
                years: this.years,
                course_id: (this.course) ? this.course.id : '',
                school_id: this.school_id,
                editable: this.editable,
                type: 'course'
            })

            if(!this.editable){
                this.form.post('/schools',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }else{
                this.form.put('/staffs/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }
        },

        hide(){
            this.showModal = false;
        }
    }
}
</script>
