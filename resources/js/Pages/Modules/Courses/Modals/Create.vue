<template>
    <b-modal v-model="showModal" title="Create Course"  header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <b-form class="customform mb-2">
            <div class="row customerform">
                
                <div class="col-md-12 mt-3">
                   <div class="form-group">
                        <label>Course:</label>
                        <input type="text" class="form-control" v-model="course.name" >
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                   <div class="form-group">
                        <label>Abbreviation:</label>
                        <input type="text" class="form-control" v-model="course.name" >
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                   <div class="form-group">
                        <label>Others:</label>
                        <input type="text" class="form-control" v-model="course.name" >
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
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components: { Multiselect, DatePicker },
    props: ['semesters','type'],
    data(){
        return {
            showModal: false,
            course: {
                name: '',
                abbreviation: '',
                others: '',
                is_active: true
            },
            form: {},
            editable: false,
        }
    },
    methods : {
        show() {
            this.showModal = true;
        },

        create(){
            this.form = this.$inertia.form({
                school_id: this.id,
                academic_year: (this.semester.from) ? this.academic_year : '',
                start_at: (this.semester.start != '') ? new Date(this.semester.start).toLocaleDateString("af-ZA") : '',
                end_at: (this.semester.end != '') ? new Date(this.semester.end.getFullYear(),this.semester.end.getMonth() + 1, 0).toLocaleDateString("af-ZA"): '',
                year: this.year,
                semester_id: this.semester.semester.id,
                editable: false,
                type: 'semester'
            })

            this.form.post('/schools',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },

        hide(){
            this.showModal = false;
        },

    }
}
</script>
