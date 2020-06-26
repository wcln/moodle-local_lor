<template>
    <div class="search-form">
        <div class="field is-grouped is-grouped-centered">
            <div class="field has-addons">
                <div class="control">
                    <div class="select is-primary is-medium">
                        <select v-model="typeFilter" aria-label="Resource type">
                            <option value="all">All resources</option>
                            <option v-for="resourceType in resourceTypes" :value="resourceType.value">{{resourceType.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="control has-icons-left">
                    <input v-model="keywords" id="search" class="input is-medium is-primary" type="text" placeholder="Search all resources" aria-label="Search all resources" @keyup.enter="search">
                    <span class="icon is-left">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </span>
                </div>
                <div class="control">
                    <a class="button is-primary is-medium" @click="search">
                        Search
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "SearchForm",
        computed: {
            ...mapState(['filters', 'resourceTypes']),
            keywords: {
                get() {
                    return this.$store.state.filters.keywords;
                },
                set(keywords) {
                    this.$store.commit('setFilters', {keywords: keywords});
                }
            },
            typeFilter: {
                get() {
                    return this.$store.state.filters.type;
                },
                set(typeFilter) {
                    this.$store.commit('setFilters', {type: typeFilter});
                }
            }
        },
        methods: {
            search() {
                this.$store.dispatch('getResources', {});
            }
        },
        created() {
            this.$store.dispatch('getResourceTypes');
        }
    }
</script>

<style scoped lang="scss">
    @media (min-width: 1024px) {
        #search {
            width: 500px;
        }
    }
</style>
