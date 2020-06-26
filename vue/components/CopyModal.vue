<template>
    <div class="modal is-active">
        <div class="modal-background" @click="$emit('close')"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{title}}</p>
                <button class="delete" aria-label="close" @click="$emit('close')"></button>
            </header>
            <section class="modal-card-body">
                <textarea readonly class="textarea" :rows="rows" v-html="content"></textarea>
            </section>
            <footer class="modal-card-foot">
                <a id="copy-btn" class="button is-success" :disabled="copied" @click="copyContentToClipboard($event)">
                    <span v-if="copied">{{strings.copied}}</span>
                    <span v-else>{{strings.copy_to_clipboard}}</span>
                </a>
                <a class="button" @click="$emit('close')">{{strings.close_modal}}</a>
            </footer>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "CopyModal",
        props: {
          content: String,
          title: String,
          rows: {
              type: Number,
              default: 10
          }
        },
        data() {
            return {
                copied: false
            }
        },
        computed: mapState(['strings']),
        methods: {

            /**
             * Copy the text area content to the clipboard
             */
            copyContentToClipboard() {
                if (! this.copied) {
                    const textArea = this.$el.querySelector('textarea');
                    textArea.focus();
                    textArea.select();
                    document.execCommand('copy');
                    this.copied = true;
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    .modal .textarea {
        box-sizing: border-box;
    }
</style>
