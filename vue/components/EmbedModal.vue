<template>
  <div class="modal is-active">
    <div class="modal-background" @click="$emit('close')"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">{{title}}</p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <section class="modal-card-body">

        <!-- Tabs -->
        <div class="tabs is-boxed">
          <ul>
            <li :class="{'is-active': activeTabIndex === 0}">
              <a @click="activeTabIndex = 0">
                <span class="icon is-small"><i class="fas fa-code" aria-hidden="true"></i></span>
                <span>HTML</span>
              </a>
            </li>
            <li :class="{'is-active': activeTabIndex === 1}">
              <a @click="activeTabIndex = 1">
                <span class="icon is-small"><i class="fab fa-google" aria-hidden="true"></i></span>
                <span>Google classroom</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Embed HTML -->
        <div class="embed-html" v-if="activeTabIndex === 0">
          <textarea aria-label="Content to copy" readonly class="textarea" :rows="rows" v-html="resource.embed"></textarea>

          <a id="copy-btn" class="button is-success" :disabled="copied" @click="copyContentToClipboard($event)">
            <span v-if="copied">{{strings.copied}}</span>
            <span v-else>{{strings.copy_to_clipboard}}</span>
          </a>
        </div>

        <!-- Embed Google classroom -->
        <div class="embed-google-classroom has-text-centered" :style="{display: (activeTabIndex === 1) ? 'block' : 'none'}">
          <p class="mb-1">Click the button below to share this resource on Google Classroom:</p>
          <div
              id="g-sharetoclassroom"
              class="g-sharetoclassroom"
              data-size="32"
              :data-url="resource.url"
              :data-title="resource.name"
          >
          </div>
        </div>

      </section>
      <footer class="modal-card-foot">
        <a class="button" @click="$emit('close')">{{strings.close_modal}}</a>
      </footer>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "EmbedModal",
  props: {
    title: String,
    rows: {
      type: Number,
      default: 10
    },
    resource: Object
  },
  data() {
    return {
      copied: false,
      activeTabIndex: 0
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
  },
  mounted() {
    let googleClassroomScript = document.createElement('script');
    googleClassroomScript.setAttribute('src', "https://apis.google.com/js/platform.js");
    document.head.appendChild(googleClassroomScript);
  },
}
</script>

<style scoped lang="scss">
.modal .textarea {
  box-sizing: border-box;
}

#copy-btn {
  margin-top: 1rem;
}

footer > a.button {

}
</style>
