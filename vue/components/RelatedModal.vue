<template>
  <div class="modal is-active">
    <div class="modal-background" @click="$emit('close')"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Related resources</p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <section class="modal-card-body">
        <div class="columns is-multiline is-centered">
          <div class="column is-four-fifths" v-for="relatedItem in relatedItems">
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
                    <div class="column is-two-thirds">
                      <div class="card-content">
                        <a :href="relatedItem.url" class="title is-5">
                          <p>{{relatedItem.title}}</p>
                        </a>
                        <a class="course-tag" :href="course.url" v-for="course in relatedItem.courses"><span class="tag">{{course.title}}</span></a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
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

export default {
  name: "RelatedModal",
  computed: {
    ...mapState(['strings'])
  },
  props: {
    resource: Object
  },
  data() {
    return {
      relatedItems: [
        {
          title: "Some title",
          url: "#",
          image: "",
          courses: [
            {title: "MATH11", url: "#"},
            {title: "COSC303", url: "#"}
          ]
        },
        {
          title: "Another title",
          image: "",
          courses: [
            {title: "Earth science", url: "#"},
            {title: "SS9", url: "#"}
          ]
        },
      ]
    }
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
</style>
