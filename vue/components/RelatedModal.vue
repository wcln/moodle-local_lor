<template>
  <div class="modal is-active">
    <div class="modal-background" @click="$emit('close')"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Related resources</p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <section class="modal-card-body">
        <p class="has-text-centered">{{strings.related_help}}</p>
        <div class="columns is-multiline is-centered" v-for="resourceType in resourceTypes" v-if="relatedItems.length > 0">
          <section class="hero is-info is-small" v-if="relatedItems.filter(i => i.type === resourceType.value).length > 0">
            <div class="hero-body">
              <div class="container has-text-centered">
                <h3 class="title is-3"><i :class="'m-r-1 fas fa-' + resourceType.icon"></i>{{resourceType.name}}</h3>
              </div>
            </div>
          </section>
          <div class="column is-four-fifths" v-for="relatedItem in relatedItems.filter(i => i.type === resourceType.value)">
            <div class="related-card">
                <div class="card">
                  <div class="columns">
                    <div class="column is-one-third">
                      <div class="card-image">
                          <figure class="image is-4by3">
                              <img :src="relatedItem.image" alt="Image alt">
                          </figure>
                      </div>
                    </div>
                    <div class="column is-one-half">
                      <div class="card-content">
                        <a :href="relatedItem.url" class="title is-5">
                          <p>{{relatedItem.name}}</p>
                        </a>
                        <a class="course-tag" :href="course.url" v-for="course in relatedItem.courses"><span class="tag">{{course.shortname}}</span></a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="columns is-multiline is-centered">
          <alert-message v-if="(! isLoading) && relatedItems.length === 0" :show-close="false">
            No related resources found
          </alert-message>
          <div v-if="isLoading">
            <p>Searching for related resources...</p>
            <progress class="progress is-medium is-info" max="100"></progress>
          </div>
        </div>
      </section>
      <footer class="modal-card-foot">
        <a class="button" @click="$emit('close')">{{ strings.close_modal }}</a>
      </footer>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";
import {ajax} from "../store";
import AlertMessage from "./AlertMessage";

export default {
  name: "RelatedModal",
  components: {AlertMessage},
  computed: {
    ...mapState(['strings', 'resourceTypes'])
  },
  props: {
    resource: Object
  },
  data() {
    return {
      relatedItems: [],
      isLoading: true
    }
  },
  created() {
    ajax('local_lor_get_related_items', {id: this.resource.id}).then(relatedItems => {
      this.relatedItems = relatedItems;
      this.isLoading = false;
    });
  }
}
</script>

<style scoped lang="scss">
  .course-tag {
    margin-right: .3rem;
  }

  a.title {
    display: block;
  }

  #local-lor-app .modal-card-body > p {
    margin-bottom: 2rem;
    color: grey;
    font-style: italic;
  }

  .hero {
    width: 100%;
  }

  .card-image {
    position: relative;
    top: 50%;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
  }
</style>
