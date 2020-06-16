<template>
    <div class="resource-view">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">{{strings.home}}</a></li>
                <li>
                    <router-link :to="{ name: 'resources-index' }">{{strings.search_title}}</router-link>
                </li>
                <li class="is-active"><a href="#" aria-current="page">{{strings.view_title}}</a></li>
            </ul>
        </nav>

        <div class="columns">
            <div class="column is-one-third-desktop">
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
                                <p class="title is-5">{{resource.name}}</p>
                            </div>
                        </div>
                        <div class="content">
                            <div class="description" v-html="resource.description"></div>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>{{strings.categories}}</th>
                                    <td>{{resource.categories}}</td>
                                </tr>
                                <tr>
                                    <th>{{strings.grades}}</th>
                                    <td>{{resource.grades}}</td>
                                </tr>
                                <tr>
                                    <th>{{strings.topics}}</th>
                                    <td>{{resource.topics}}</td>
                                </tr>
                                <tr>
                                    <th>{{strings.contributors}}</th>
                                    <td>{{resource.contributors}}</td>
                                </tr>
                                <tr>
                                    <th>{{strings.date_created}}</th>
                                    <td>{{resource.timecreated}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <a class="card-footer-item">
                            {{strings.share}}
                        </a>
                        <a class="card-footer-item">
                            {{strings.related}}
                        </a>
                        <a class="card-footer-item" @click="showEmbedModal()">
                            {{strings.embed}}
                        </a>
                        <a class="card-footer-item" :href="'/local/lor/item/edit.php?id=' + resource.id">
                            {{strings.edit}}
                        </a>
                    </footer>
                </div>
            </div>
            <div class="column resource-display" v-html="resource.display"></div>
        </div>

        <router-link :to="{ name: 'resources-index' }">
            <button class="button is-default">
                {{strings.back}}
            </button>
        </router-link>

        <embed-modal
                ref="embedModal"
                :content="resource.embed"
                :title="strings.embed_modal_title">
        </embed-modal>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import CopyModal from "./CopyModal";

    export default {
        name: "resource-view",
        components: {'embedModal': CopyModal},
        computed: mapState(['strings']),
        data() {
            return {
                resource: {},
            }
        },
        created() {
            this.$store.dispatch('fetchResource', this.$route.params.resourceId).then(resource => {
                this.resource = resource;
            })
        },
        methods: {
            showEmbedModal() {
                this.$refs.embedModal.show();
            }
        }
    }
</script>

<style scoped lang="scss">
    .resource-view {
        table {
            margin-top: 1em;
        }
    }
</style>
