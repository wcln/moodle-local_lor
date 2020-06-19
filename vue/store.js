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
            type: 0,
            categories: [],
            grades: [],
            sort: 'recent'
        },
        pages: 5 // TODO, this should be set when fetching resources
    },
    //strict: process.env.NODE_ENV !== 'production',
    mutations: {
        setContextID(state, id) { state.contextID = id; },
        setStrings(state, strings) { state.strings = strings; },
        setResources(state, resources) { state.resources = resources; },
        setFilters(state, filters) { state.filters = {...state.filters, ...filters} }
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
        async getResources(context, filters) {


            context.commit('setFilters', filters);

            let resources = await ajax('local_lor_get_resources', context.state.filters);
            context.commit('setResources', resources);

            window.console.log(resources);
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
