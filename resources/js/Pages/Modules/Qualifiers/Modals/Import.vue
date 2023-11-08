<template>
    <b-modal v-model="showModal" hide-footer header-class="p-3 bg-light" style="--vz-modal-width: 600px;" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-slot:header>
            <h5 class="modal-title">Import Qualifiers</h5>
            <button @click="hide()" type="button" class="btn-close" aria-label="Close"></button>
        </template>
        <b-form action="#" id="editform" class="tablelist-form" autocomplete="off">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="p-2">
                        <div class="text-center">

                            <div class="avatar-md mx-auto">
                                <div class="avatar-title rounded-circle bg-light">
                                    <i class="bx bxs-file h1 mb-0 text-primary"></i>
                                </div>
                            </div>
                            <div class="p-2 mt-4">
                                <form @submit.prevent="preview" enctype="multipart/form-data">
                                    <h5>Import using Excel</h5>
                                    <p v-if="!result" class="text-muted">Please click preview to generate the entry</p>
                                    <p v-else>Import Successful</p>
                                    <input class="mt-2 mb-4" type="file" @change="uploadFieldChange">
                                    <!-- <span v-if="errors['files.'+0]" class="haveerror">(required)</span> -->
                                    
                                    <div  v-if="!result">
                                        <br />
                                        <span v-if="isLoading"><i class='bx bx-loader-circle bx-spin mt-2'></i><span class="text-muted ms-1 mt-n4">Loading ... </span></span>
                                        <center v-if="users.length > 0"><span class="text-muted mb-4">Are you sure you want to import the <span class="font-weight-bold text-danger"> {{ users.length }}</span> scholars?</span></center>
                                        <br />
                                    </div>

                                    <div class="row g-0 text-center" v-if="result">
                                        <div class="col-sm-4">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span>{{success.length}}</span></h5>
                                                <p class="text-success fw-semibold mb-0">Success</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span>{{failed.length}}</span></h5>
                                                <p class="text-danger fw-semibold mb-0">Failed</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span>{{duplicate.length}}</span></h5>
                                                <p class="text-warning fw-semibold mb-0">Duplicate</p>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="mt-4" v-else>
                                        <button type="submit" :disabled="users.length > 0" class="btn btn-light w-lg me-1">Preview</button>
                                        <button @click="sync" type="button" :disabled="users.length == 0" class="btn btn-primary w-lg">Import</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-form>

    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            users: [],
            entry: '',
            success: 0,
            failed: 0,
            duplicate: 0,
            isLoading: false,
            result: false
        }
    },
    methods : {
        show(){
            this.showModal = true;
        },
        uploadFieldChange(e) {
            e.preventDefault();
            var file = e.target.files || e.dataTransfer.files;
            this.entry = file;
        },
        preview(){
            this.isLoading = true;
            let data = new FormData();
            data.append('import_file', this.entry[0]);
            data.append('type', 'preview');

            axios.post(this.currentUrl + '/qualifiers', data)
            .then(response => {
                this.users = response.data;
                this.isLoading = false;
            })
            .catch(error => {
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                    this.isLoading = false;
                }
            });
        },
        sync(){
            this.isLoading = true;
            this.qualifiers = this.users;

            axios.post(this.currentUrl + '/qualifiers', {
                lists: this.qualifiers,
                type: 'import'
            })
            .then(response => {
                this.isLoading = false;
                this.result = true;
                this.success = response.data.success;
                this.failed = response.data.failed;
                this.duplicate = response.data.duplicate;
                this.$emit('info',true);
            })
            .catch(error => {
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                    this.isLoading = false;
                }
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
