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
                    <p class="title is-5">{{ resource.name }}</p>
                </div>
            </div>
            <div class="content" v-html="resource.description">
            </div>
        </div>
        <footer class="card-footer">
            <a href="#" class="card-footer-item">View</a>
        </footer>
    </div>
</template>

<script>
    export default {
        name: "resource-card",
        props: ['resource']
    }
</script>

<style scoped lang="scss">

</style>
