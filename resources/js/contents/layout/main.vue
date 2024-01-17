<template>
    <div id="wrapper">
    <headerPart></headerPart>
    <!-- class="d-flex flex-column" -->
    <div id="content-wrapper" >
        <!-- Main Content -->
        <div id="content">
            
            <!-- Begin Page Content -->
            <div class="container-fluid"><router-view></router-view></div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

    </div>

</div>
</template>

<script>
    import headerPart from './header.vue';

    export default {
        components:{ 
            headerPart, 
        },
    }
</script>