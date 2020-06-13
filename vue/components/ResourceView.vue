<template>
    <div class="resource-view">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">{{strings.home}}</a></li>
                <li><router-link :to="{ name: 'resources-index' }">{{strings.search_title}}</router-link></li>
                <li class="is-active"><a href="#" aria-current="page">{{strings.view_title}}</a></li>
            </ul>
        </nav>

        <h1>This is the resource view!</h1>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "resource-view",
        computed: mapState(['strings']),
        data() {
            return {
                resource: {}
            }
        },
        beforeMount() {
            this.$store.dispatch('fetchResource', this.$route.params.resourceId).then(resource => {
                this.resource = resource;
            })
        }
    }
</script>

<style scoped>

</style>
