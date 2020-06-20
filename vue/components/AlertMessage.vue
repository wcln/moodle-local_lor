<template>
    <article class="message is-info">
        <div class="message-header">
            <p><slot name="header"></slot></p>
            <button v-if="showClose" class="delete" aria-label="delete" @click="$emit('close')"></button>
        </div>
        <div class="message-body">
            <slot></slot>
        </div>
    </article>
</template>

<script>
    export default {
        name: "NoResultsFound",
        props: {
            showClose: {
                type: Boolean,
                default: true
            }
        }
    }
</script>

<style scoped>

</style>
