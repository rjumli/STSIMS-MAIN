<template>
<Head title="Staffs" />
<PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <Sidebar />
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)">
            <Lists :dropdowns="dropdowns"/>
        </div>
    </div>
</template>
<script>
import Sidebar from './Sidebar.vue';
import Lists from './Lists.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components : { PageHeader, Lists, Sidebar },
    props: ['dropdowns'],
    data() {
        return {
            title: "List of Schools",
            items: [{text: "Lists",href: "/",},{text: "School",active: true,},]
        };
    }
}
</script>
<style>
.file-manager-sidebar {
  min-width: 450px;
  max-width: 450px;
  height: calc(100vh - 180px);
}
</style>
