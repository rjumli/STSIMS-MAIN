<template>
<div class="p-4 d-flex flex-column h-100">
    
</div>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            lists: []
        }
    },
    mounted(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/staffs';
            axios.get(page_url,{
                params : {
                    type: 'logs'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;          
                }
            })
            .catch(err => console.log(err));
        },
    }
}
</script>