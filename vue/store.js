import Vue from 'vue';
import Vuex from 'vuex';
import moodleAjax from 'core/ajax';
import moodleStorage from 'core/localstorage';
import Notification from 'core/notification';
import $ from 'jquery';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        contextID: 0,
        strings: {},
        resources: [],
        filters: {
            page: 0,
            keywords: "",
            type: "all",
            categories: [],
            grades: [],
            sort: 'recent' // Sort can be 'recent' or 'alphabetical'
        },
        pages: 1,
        resourceCount: 0,
        resourceTypes: [],
        categories: [],
        grades: [],
        user: {
            isAdmin: false
        },
        resourcesLoading: false
    },
    //strict: process.env.NODE_ENV !== 'production',
    mutations: {
        setContextID(state, id) {
            state.contextID = id;
        },
        setStrings(state, strings) {
            state.strings = strings;
        },
        setUser(state, user) {
            state.user = {...state.user, ...user};
        },
        setResources(state, resources) {
            state.resources = resources;
        },
        setFilters(state, filters) {
            state.filters = {...state.filters, ...filters};
        },
        setResourceTypes(state, resourceTypes) {
            state.resourceTypes = resourceTypes;
        },
        setCategories(state, categories) {
            state.categories = categories;
        },
        setGrades(state, grades) {
            state.grades = grades;
        },
        setPages(state, pages) {
            state.pages = pages;
        },
        setResourceCount(state, resourceCount) {
            state.resourceCount = resourceCount;
        },
        setLoading(state, isLoading) {
            state.resourcesLoading = isLoading;
        }
    },
    actions: {
        async loadComponentStrings(context) {
            const lang = $('html').attr('lang').replace(/-/g, '_');
            const cacheKey = 'local_lor/strings/' + lang;
            const cachedStrings = moodleStorage.get(cacheKey);
            if (cachedStrings) {
                context.commit('setStrings', JSON.parse(cachedStrings));
            } else {
                const request = {
                    methodname: 'core_get_component_strings',
                    args: {
                        'component': 'local_lor',
                        lang,
                    },
                };
                const loadedStrings = await moodleAjax.call([request])[0];
                let strings = {};
                loadedStrings.forEach((s) => {
                    strings[s.stringid] = s.string;
                });
                context.commit('setStrings', strings);
                moodleStorage.set(cacheKey, JSON.stringify(strings));
            }
        },
        async loadUser(context) {
              const user = await ajax('local_lor_get_user', {});
              context.commit('setUser', user);
        },
        async getResources(context, filters) {
            context.commit('setLoading', true);
            context.commit('setFilters', filters);

            const results = await ajax('local_lor_get_resources', context.state.filters);
            context.commit('setResources', results.resources);
            context.commit('setPages', results.pages);
            context.commit('setResourceCount', results.resource_count);
            context.commit('setLoading', false);
        },
        async getResourceTypes(context) {
            const resourceTypes = await ajax('local_lor_get_resource_types', {});
            context.commit('setResourceTypes', resourceTypes);
        },
        async getCategories(context) {
            const categories = await ajax('local_lor_get_categories', {});
            context.commit('setCategories', categories);
        },
        async getGrades(context) {
            const grades = await ajax('local_lor_get_grades', {});
            context.commit('setGrades', grades);
        },
    }
});

/**
 * Single ajax call to Moodle
 */
export async function ajax(method, args) {
    const request = {
        methodname: method,
        args: args
    };

    try {
        return await moodleAjax.call([request])[0];
    } catch (e) {
        Notification.exception(e);
        throw e;
    }
}
