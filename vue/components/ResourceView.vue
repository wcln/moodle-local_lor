<template>
  <div class="resource-view">
    <nav class="breadcrumb" aria-label="breadcrumbs">
      <ul>
        <li><a href="/">{{ strings.home }}</a></li>
        <li>
          <router-link :to="{ name: 'resources-index' }">{{ strings.search_title }}</router-link>
        </li>
        <li class="is-active"><a href="#" aria-current="page">{{ strings.view_title }}</a></li>
      </ul>
    </nav>

    <div class="container has-text-centered">
      <h1 class="title">{{ resource.name }}</h1>
    </div>

    <a class="button is-info" @click="showDetails = ! showDetails">
      <template v-if="showDetails">
        <span class="icon"><i class="fas fa-chevron-circle-left"></i></span>
        <span>Hide details</span>
      </template>
      <template v-else>
        <span class="icon"><i class="fas fa-chevron-circle-right"></i></span>
        <span>Show details</span>
      </template>
    </a>

    <div class="columns">

      <transition name="fade">
        <div class="column is-one-third-desktop" id="resource-details" v-if="showDetails">
          <div class="card">
            <!-- Uncomment the lines below if you wish to show the card image -->
            <!--                    <div class="card-image">-->
            <!--                        <figure class="image is-4by3">-->
            <!--                            <img :src="resource.image" alt="Image alt">-->
            <!--                        </figure>-->
            <!--                    </div>-->
            <div class="card-content">
              <div class="media">
                <div class="media-left">
                  <i :class="'has-text-primary fas fa-' + icon"></i>
                </div>
                <div class="media-content">
                  <p class="title is-5">{{ resource.name }}</p>
                </div>
              </div>
              <div class="content">
                <div class="description" v-html="resource.description"></div>
                <table class="table">
                  <tbody>
                  <tr>
                    <th>{{ strings.categories }}</th>
                    <td>{{ resource.categories }}</td>
                  </tr>
                  <tr>
                    <th>{{ strings.grades }}</th>
                    <td>{{ resource.grades }}</td>
                  </tr>
                  <tr>
                    <th>{{ strings.topics }}</th>
                    <td id="topics">{{ resource.topics }}</td>
                  </tr>
                  <tr>
                    <th>{{ strings.contributors }}</th>
                    <td>{{ resource.contributors }}</td>
                  </tr>
                  <tr>
                    <th>{{ strings.date_created }}</th>
                    <td>{{ resource.timecreated }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <footer class="card-footer">
              <a class="card-footer-item" @click="showShareModal = true">
                <i class="fas fa-share-square"></i>
                {{ strings.share }}
              </a>
              <a class="card-footer-item" @click="showRelatedModal = true">
                <i class="fas fa-share-alt"></i>
                {{ strings.related }}
              </a>
              <a class="card-footer-item" @click="showEmbedModal = true">
                <i class="fas fa-code"></i>
                {{ strings.embed }}
              </a>
              <a v-if="user.isAdmin" class="card-footer-item has-text-info"
                 :href="'/local/lor/item/edit.php?id=' + resource.id">
                <i class="fas fa-edit"></i>
                {{ strings.edit }}
              </a>
              <a v-if="user.isAdmin" class="card-footer-item has-text-danger"
                 :href="'/local/lor/item/delete.php?id=' + resource.id">
                <i class="fas fa-trash"></i>
                {{ strings.delete }}
              </a>
            </footer>
          </div>
        </div>
      </transition>

      <div class="column resource-display">
        <div class="card" >
          <div class="card-content" v-html="resource.display">
          </div>
        </div>
      </div>
    </div>

    <router-link :to="{ name: 'resources-index' }">
      <a class="button is-primary">
                <span class="icon">
                    <i class="fas fa-chevron-circle-left"></i>
                </span>
        <span>
                    Back to resources
                </span>
      </a>
    </router-link>

    <embed-modal
        ref="embedModal"
        v-if="showEmbedModal"
        @close="showEmbedModal = false"
        :resource="resource"
        :title="strings.embed_modal_title">
    </embed-modal>

    <share-modal
        ref="shareModal"
        v-if="showShareModal"
        :resource="resource"
        @close="showShareModal = false">
    </share-modal>

    <related-modal
        ref="relatedModal"
        v-if="showRelatedModal"
        @close="showRelatedModal = false"
        :resource="resource"
    >
    </related-modal>
  </div>
</template>

<script>
import {mapState} from "vuex";
import ShareModal from "./ShareModal";
import EmbedModal from "./EmbedModal";
import {ajax} from "../store";
import RelatedModal from "./RelatedModal";

export default {
  name: "resource-view",
  components: {RelatedModal, ShareModal, EmbedModal},
  computed: {
    ...mapState(['strings', 'resourceTypes', 'user']),
    icon() {
      let resourceType = this.resourceTypes.find(type => {
        return type.value === this.resource.type;
      });
      if (resourceType !== undefined) {
        return resourceType.icon;
      }

      return false;
    }
  },
  data() {
    return {
      resource: {},
      showShareModal: false,
      showEmbedModal: false,
      showRelatedModal: false,
      showDetails: false
    }
  },
  created() {
    ajax('local_lor_get_resource', {id: this.$route.params.resourceId}).then(resource => {
      this.resource = resource;
    });

    this.$store.dispatch('getResourceTypes');
  }
}
</script>

<style scoped lang="scss">

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

#local-lor-app .columns {
  margin-top: 1rem;
}

#local-lor-app .resource-view .title {
  margin-bottom: 1rem;
}

#resource-details {
  table {
    margin-top: 1em;
  }

  .media-left > i {
    font-size: 36px;
  }

  #topics {
    text-transform: capitalize;
  }

  .card-image .image img {
    object-fit: cover;
  }
}

.card-footer-item > i {
  margin-right: 1rem;
}
</style>
