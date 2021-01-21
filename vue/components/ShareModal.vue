<template>
  <div class="modal is-active">
    <div class="modal-background" @click="$emit('close')"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">{{ title }}</p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <section class="modal-card-body">
        <div class="share-buttons mb-3">
          <span class="mr-2"><i>Share:</i></span>
          <div
              id="g-sharetoclassroom"
              class="g-sharetoclassroom"
              data-size="24"
              :data-url="resource.url"
              :data-title="resource.name"
          >
          </div>
          <div
              class="teams-share-button"
              :data-href="resource.url"
              data-icon-px-size="24">
          </div>
        </div>
        <input class="input" type="text" aria-label="Content to copy" readonly :rows="rows" :value="resource.url">
      </section>
      <footer class="modal-card-foot">
        <a id="copy-btn" class="button is-success" :disabled="copied" @click="copyContentToClipboard($event)">
          <span v-if="copied">{{ strings.copied }}</span>
          <span v-else>{{ strings.copy_to_clipboard }}</span>
        </a>
        <a class="button" @click="$emit('close')">{{ strings.close_modal }}</a>
      </footer>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "ShareModal",
  props: {
    resource: Object,
    title: {
      type: String,
      default: "Share this resource"
    },
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
      if (!this.copied) {
        const textArea = this.$el.querySelector('.modal-card-body input');
        textArea.focus();
        textArea.select();
        document.execCommand('copy');
        this.copied = true;
      }
    },
    loadScript(url) {
      let script = document.createElement('script');
      script.setAttribute('src', url);
      document.head.appendChild(script);
    }
  },
  mounted() {
    this.loadScript("https://apis.google.com/js/platform.js");
    this.loadScript("https://teams.microsoft.com/share/launcher.js");
  },
}
</script>

<style scoped lang="scss">
#local-lor-app {
  .modal .textarea {
    box-sizing: border-box;
  }

  .modal-card-body {

    .share-buttons {
      display: flex;
      align-items: center;
    }

    input {
      width: 90%;
    }
  }
}
</style>
