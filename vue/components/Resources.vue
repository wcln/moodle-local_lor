<template>
    <div class="resources-index">

        <!-- Breadcrumbs -->
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">{{strings.home}}</a></li>
                <li class="is-active"><a href="#" aria-current="page">{{strings.search_title}}</a></li>
            </ul>
        </nav>

        <!-- Page title -->
        <h1 class="title is-1">{{strings.search_title}}</h1>

        <!-- Add a resource button -->
        <a class="button" href="/local/lor/item/add.php">{{strings.add_item_link}}</a>

        <!-- Search form -->
        <search-form></search-form>

        <!-- Resources -->
        <div class="columns is-multiline">
            <div class="column is-one-fifth-desktop is-one-quarter-tablet" v-for="resource in resources">
                <resource-card :resource="resource"></resource-card>
            </div>
        </div>

        <!-- Pagination bar -->
        <nav class="pagination" role="navigation" aria-label="pagination">
            <a class="pagination-previous">{{strings.previous_page}}</a>
            <a class="pagination-next">{{strings.next_page}}</a>
            <ul class="pagination-list">
                <li>
                    <a class="pagination-link" aria-label="Goto page 1">1</a>
                </li>
                <li>
                    <span class="pagination-ellipsis">&hellip;</span>
                </li>
                <li>
                    <a class="pagination-link" aria-label="Goto page 45">45</a>
                </li>
                <li>
                    <a class="pagination-link is-current" aria-label="Page 46" aria-current="page">46</a>
                </li>
                <li>
                    <a class="pagination-link" aria-label="Goto page 47">47</a>
                </li>
                <li>
                    <span class="pagination-ellipsis">&hellip;</span>
                </li>
                <li>
                    <a class="pagination-link" aria-label="Goto page 86">86</a>
                </li>
            </ul>
        </nav>

    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import ResourceCard from "./ResourceCard";
    import SearchForm from "./SearchForm";

    export default {
        name: "resources-index",
        components: {SearchForm, ResourceCard},
        computed: mapState(['strings', 'resources']),
    }
</script>

<style scoped lang="scss">

</style>
