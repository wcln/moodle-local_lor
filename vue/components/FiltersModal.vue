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
                        ></multiselect>
                    </div>
                </div>

                <!-- Sort by select -->
                <div class="field">
                    <label class="label">Sort by</label>
                    <div class="control">
                        <div class="select is-medium">
                            <select>
                                <option>Recently added</option>
                                <option>Alphabetical</option>
                            </select>
                        </div>
                    </div>
                </div>

            </section>
            <footer class="modal-card-foot">
                <a class="button is-success">
                    Apply
                </a>
                <a class="button" @click="$emit('close')">{{strings.close_modal}}</a>
            </footer>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import Multiselect from 'vue-multiselect';

    export default {
        name: "FiltersModal",
        computed: mapState(['strings']),
        components: { Multiselect },
        data() {
            return {
                selectedCategories: null,
                categories: ['Math', 'Science', 'Physics', 'Earth science', 'Other'],
                selectedGrades: null,
                grades: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            }
        }
    }
</script>

<style scoped lang="scss">
    #local-lor-app .modal .modal-card-body {
        padding-bottom: 15rem;
    }
</style>
