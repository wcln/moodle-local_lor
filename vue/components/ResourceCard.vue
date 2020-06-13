<template>
    <div class="card">
        <div class="card-image">
            <figure class="image is-4by3">
                <img :src="resource.image" alt="Image alt">
            </figure>
        </div>
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <figure class="image is-48x48">
                        <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                    </figure>
                </div>
                <div class="media-content">
                    <p class="title is-5">{{resource.name}}</p>
                </div>
            </div>
            <div class="content" v-html="resource.description">
            </div>
        </div>
        <footer class="card-footer">
            <router-link class="card-footer-item" :to="{ name: 'resource-view', params: { resourceId: resource.id } }">
                {{strings.view}}
            </router-link>
        </footer>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "resource-card",
        props: ['resource'],
        computed: mapState(['strings']),
    }
</script>

<style scoped lang="scss">

</style>
