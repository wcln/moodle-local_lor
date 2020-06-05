<template>
    <div class="resources-index">
        <h1 class="title is-1">{{strings.search_title}}</h1>
        <p>This content is coming from a Vue component</p>
        <button class="button is-dark">Click me</button>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: "resources-index",
        computed: mapState(['strings', 'resources']),
    }
</script>

<style scoped lang="scss">
    .resources-index {
        p {
            color: red;
        }
    }
</style>
