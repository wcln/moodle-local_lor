<template>
    <nav class="pagination" role="navigation" aria-label="pagination">
        <a class="pagination-previous" :disabled="currentPage <= 0" @click="changePage(currentPage - 1)">{{strings.previous_page}}</a>
        <a class="pagination-next" :disabled="currentPage >= pages - 1" @click="changePage(currentPage + 1)">{{strings.next_page}}</a>
        <ul class="pagination-list">
            <li v-for="page in Array(pages).keys()">
                <a v-if="currentPage === page" class="pagination-link is-current" :aria-label="'Goto page' + page" aria-current="page">{{page + 1}}</a>
                <a v-else @click="changePage(page)" class="pagination-link" :aria-label="'Goto page' + (page + 1)">{{page + 1}}</a>
            </li>
        </ul>
<!--        <ul v-else class="pagination-list">-->
<!--            <li v-show="currentPage > 1">-->
<!--                <a class="pagination-link" aria-label="Goto page 1">1</a>-->
<!--            </li>-->
<!--            <li v-show="currentPage > 3">-->
<!--                <span class="pagination-ellipsis">&hellip;</span>-->
<!--            </li>-->
<!--            <li v-show="currentPage > 0">-->
<!--                <a class="pagination-link" :aria-label="'Goto page' + (currentPage) ">{{currentPage}}</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a class="pagination-link is-current" :aria-label="'Goto page' + (currentPage + 1) ">{{currentPage + 1}}</a>-->
<!--            </li>-->
<!--            <li v-show="currentPage < pages - 1">-->
<!--                <a class="pagination-link" :aria-label="'Goto page' + (currentPage + 2) ">{{currentPage + 2}}</a>-->
<!--            </li>-->
<!--            <li v-show="pages - currentPage > 3">-->
<!--                <span class="pagination-ellipsis">&hellip;</span>-->
<!--            </li>-->
<!--            <li v-show="pages - currentPage > 2">-->
<!--                <a class="pagination-link" :aria-label="'Goto page' + pages">{{pages}}</a>-->
<!--            </li>-->
<!--        </ul>-->
    </nav>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "PaginationBar",
        computed: {
            ...mapState(['pages', 'filters', 'strings']),
            currentPage: {
                get() {
                    return this.$store.state.filters.page;
                },
                set(currentPage) {
                    this.$store.commit('setFilters', {page: currentPage});
                }
            }
        },
        data() {
            return {
                showNextButton: true,
                showPreviousButton: true
            }
        },
        methods: {
            changePage(page) {
                if (page >= 0 && page < this.pages) {
                    this.currentPage = page;
                    this.$store.dispatch('getResources', {});
                }
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
