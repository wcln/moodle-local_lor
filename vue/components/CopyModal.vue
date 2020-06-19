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
                <a id="copy-btn" class="button is-success" @click="copyContentToClipboard($event)">
                    {{strings.copy_to_clipboard}}
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
        computed: mapState(['strings']),
        methods: {

            /**
             * Copy the text area content to the clipboard
             */
            copyContentToClipboard() {
                const textArea = this.$el.querySelector('textarea');
                textArea.focus();
                textArea.select();
                document.execCommand('copy');

                this.disableCopyButton();
            },

            /**
             *  Disable the copy button and change the text
             */
            disableCopyButton() {
                const copyButton = this.$el.querySelector('#copy-btn');
                copyButton.innerHTML = this.strings.copied;
                copyButton.disabled = true;
            },

            /**
             * Enable the copy button and reset the text
             */
            enableCopyButton() {
                const copyButton = this.$el.querySelector('#copy-btn');
                if (copyButton.disabled) {
                    copyButton.disabled = false;
                    copyButton.innerHTML = this.strings.copy_to_clipboard;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .modal .textarea {
        box-sizing: border-box;
    }
</style>
