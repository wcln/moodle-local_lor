<template>
    <div class="card">
        <div class="card-image">
            <figure class="image is-4by3">
                <img :src="resource.image" alt="Image alt">
            </figure>
        </div>
        <div class="card-content">
            <div class="media">
                <div class="media-left">
                    <span class="icon is-large has-text-primary">
                        <i :class="'fas fa-' + icon"></i>
                    </span>
                </div>
                <div class="media-content">
                    <p class="title is-5">{{resource.name}}</p>
                </div>
            </div>
            <div class="content" v-html="resource.description">
            </div>
        </div>
        <footer class="card-footer">
            <router-link class="card-footer-item" :to="{ name: 'resource-view', params: { resourceId: resource.id } }">
                {{strings.view}}
            </router-link>
        </footer>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "resource-card",
        props: ['resource'],
        computed: {
            ...mapState(['strings', 'resourceTypes']),
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
    }
</script>

<style scoped lang="scss">
    .media-left > .icon > i {
        font-size: 36px;
    }
</style>
