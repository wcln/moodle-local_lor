<template>
    <div class="search-form">
        <div class="field is-grouped is-grouped-centered">
            <div class="field has-addons">
                <div class="control">
                    <div class="select is-primary is-medium">
                        <select>
                            <option>All resources</option>
                            <option>Games / media</option>
                            <option>Inquiry projects</option>
                            <option>Videos</option>
                            <option>Group activities</option>
                        </select>
                    </div>
                </div>
                <div class="control has-icons-left">
                    <input class="input is-medium is-primary" type="text" placeholder="Search all resources" aria-label="Search all resources">
                    <span class="icon is-left">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </span>
                </div>
                <div class="control">
                    <a class="button is-info is-medium">
                        Search
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SearchForm",
    }
</script>

<style scoped lang="scss">

</style>
