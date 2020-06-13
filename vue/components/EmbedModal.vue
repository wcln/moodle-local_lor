<template>
    <div class="modal" v-bind:class="{ 'is-active': isActive }">
        <div class="modal-background"></div>
        <div class="modal-card" >
            <header class="modal-card-head">
                <p class="modal-card-title">Embed this resource</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <textarea class="textarea" rows="10" v-html="embedHtml"></textarea>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success">Copy to clipboard</button>
                <button class="button">Close</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        name: "EmbedModal",
        props: ['embedHtml', 'isActive']
    }
</script>

<style scoped>

</style>
