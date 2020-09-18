<template>
    <div class="resources-index">

        <!-- Breadcrumbs -->
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">{{strings.home}}</a></li>
                <li class="is-active"><a href="#" aria-current="page">{{strings.search_title}}</a></li>
            </ul>
        </nav>

        <!-- Search form -->
        <section class="hero is-primary is-medium">
            <div class="hero-head has-text-right">
                <!-- Add a resource button -->
                <a v-if="user.isAdmin" class="button is-primary is-inverted" href="/local/lor/item/add.php">
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span>{{strings.add_item_link}}</span>
                </a>
                <!-- Filters button -->
                <a class="button is-primary is-inverted" @click="showFiltersModal = true">
                    <span class="icon">
                        <i class="fas fa-filter"></i>
                    </span>
                    <span>Filters</span>
                </a>
            </div>

            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title is-size-1">Learning Resources</h1>
                    <search-form></search-form>
                </div>
            </div>

        </section>

        <!-- Resources -->
        <div class="columns is-multiline resource-results is-centered" id="resources">
            <div class="column is-one-fifth-desktop is-one-quarter-tablet" v-for="resource in resources">
                <resource-card :resource="resource"></resource-card>
            </div>
        </div>

        <!-- Show a message if no results were found -->
        <div class="columns is-centered no-results-message" v-if="resources.length === 0">
            <alert-message class="column is-one-third-desktop" :show-close="false">
                <template slot="header">No results found</template>
                No results were found which match your search filters. Please try again.
            </alert-message>
        </div>

        <!-- Pagination bar -->
        <pagination-bar></pagination-bar>

        <!-- Filters modal (hidden by default) -->
        <filters-modal v-if="showFiltersModal" @close="showFiltersModal = false"></filters-modal>

    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import ResourceCard from "./ResourceCard";
    import SearchForm from "./SearchForm";
    import FiltersModal from "./FiltersModal";
    import AlertMessage from "./AlertMessage";
    import PaginationBar from "./PaginationBar";

    export default {
        name: "resources-index",
        components: {PaginationBar, SearchForm, ResourceCard, FiltersModal, AlertMessage },
        computed: {
            ...mapState(['strings', 'resources', 'pages', 'filters', 'user', 'resourceTypes']),
        },
        data() {
            return {
                showFiltersModal: false,
            }
        },
        mounted() {
            if (this.$route.params.type !== undefined) {
                this.$store.commit('setFilters', {type: this.$route.params.type});
            }

            this.$store.dispatch('getResources', {});
        }
    }
</script>

<style scoped lang="scss">
    .hero {
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('/local/lor/vue/assets/images/search_background.jpg');
        background-size: cover;

        .hero-head {
            padding: 1rem 1rem 0 0;
        }
    }

    /* ID is required to override Bulma .columns margin styling */
    #local-lor-app .resource-results.columns {
        margin-top: 1rem;
    }

    #local-lor-app .no-results-message {
        margin-top: 2rem;
        margin-bottom: 2rem;

        article.column {
            padding: 0;
        }
    }
</style>
