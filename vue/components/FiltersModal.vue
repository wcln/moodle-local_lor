<template>
    <div class="modal is-active">
        <div class="modal-background" @click="$emit('close')"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Apply search filters</p>
                <button class="delete" aria-label="close" @click="$emit('close')"></button>
            </header>
            <section class="modal-card-body">

                <!-- Categories multiselect -->
                <div class="field">
                    <label class="label">Categories</label>
                    <div class="control">
                        <multiselect
                                v-model="selectedCategories"
                                :options="categories"
                                :multiple="true"
                                :searchable="false"
                                :max-height="200"
                                placeholder="Select categories"
                                label="name"
                                track-by="id"
                        ></multiselect>
                    </div>
                </div>

                <!-- Grades multiselect -->
                <div class="field">
                    <label class="label">Grades</label>
                    <div class="control">
                        <multiselect
                                v-model="selectedGrades"
                                :options="grades"
                                :multiple="true"
                                :searchable="false"
                                :max-height="200"
                                placeholder="Select grades"
                                label="name"
                                track-by="id"
                        ></multiselect>
                    </div>
                </div>

                <!-- Sort by select -->
                <div class="field">
                    <label class="label">Sort by</label>
                    <div class="control">
                        <div class="select is-medium">
                            <select v-model="sort" aria-label="Sort by">
                                <option v-for="sortOption in sortOptions" :value="sortOption.value">
                                    {{sortOption.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

            </section>
            <footer class="modal-card-foot">
                <a class="button is-success" @click="applyFilters">
                    Apply
                </a>
                <a class="button is-info" @click="resetFilters">
                    Reset
                </a>
                <a class="button is-info" @click="clearFilters">
                    Clear
                </a>
                <a class="button is-danger" @click="$emit('close')">{{strings.close_modal}}</a>
            </footer>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import Multiselect from 'vue-multiselect';

    export default {
        name: "FiltersModal",
        computed: mapState(['strings', 'filters', 'categories', 'grades']),
        components: {Multiselect},
        data() {
            return {
                selectedCategories: this.$store.state.filters.categories,
                selectedGrades: this.$store.state.filters.grades,
                sort: this.$store.state.filters.sort,
                // TODO, these should probably be fetched using the API
                sortOptions: [
                    {value: 'recent', name: 'Recently added'},
                    {value: 'alphabetical', name: 'Alphabetical'},
                ],
            }
        },
        methods: {
            applyFilters() {
                this.$store.commit('setFilters', {
                    categories: this.selectedCategories,
                    grades: this.selectedGrades,
                    sort: this.sort,
                    page: 0 // Reset to the first page when we apply filters
                });
                this.$emit('close');
                this.$store.dispatch('getResources', {});
            },
            resetFilters() {
                this.selectedCategories = this.$store.state.filters.categories;
                this.selectedGrades = this.$store.state.filters.grades;
                this.sort = this.$store.state.filters.sort;
            },
            clearFilters() {
                this.selectedCategories = this.selectedGrades = [];
                this.sort = 'recent';
            }
        },
        created() {
            this.$store.dispatch('getCategories');
            this.$store.dispatch('getGrades');
        }
    }
</script>

<style scoped lang="scss">
    #local-lor-app .modal .modal-card-body {
        padding-bottom: 15rem;
    }
</style>
