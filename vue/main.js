import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store';
import './scss/main.scss';
import Resources from './components/Resources'
import ResourceView from "./components/ResourceView";

function init(contextid) {
    // We need to overwrite the variable for lazy loading.
    __webpack_public_path__ = M.cfg.wwwroot + '/local/lor/amd/build/';

    Vue.use(VueRouter);

    store.commit('setContextID', contextid);
    store.dispatch('loadComponentStrings');
    store.dispatch('fetchResources');

    // You have to use child routes if you use the same component. Otherwise the component's beforeRouteUpdate
    // will not be called.
    const routes = [
        { path: '/', redirect: { name: 'resources-index' }},
        { path: '/resources', component: Resources, name: 'resources-index', meta: { title: 'search_title' } },
        { path: '/resources/view/:resourceId(\\d+)', component: ResourceView, name: 'resource-view', meta: { title: 'view_title' } }
    ];

    // base URL is /local/lor/index.php/
    const currenturl = window.location.pathname;
    const base = currenturl.substr(0, currenturl.indexOf('.php')) + '.php/';

    const router = new VueRouter({
        mode: 'history',
        routes,
        base
    });

    router.beforeEach((to, from, next) => {
        // Find a translation for the title.
        if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
            if (store.state.strings.hasOwnProperty(to.meta.title)) {
                document.title = store.state.strings[to.meta.title];
            }
        }
        next()
    });

    new Vue({
        el: '#local-lor-app',
        store,
        router,
    });
}

export { init };
