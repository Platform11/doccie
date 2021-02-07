require("./bootstrap");

import { App, plugin } from "@inertiajs/inertia-vue";
import Vue from "vue";
import Lang from "lang.js";
import store from "./store";
import { flareVue } from "@flareapp/flare-vue";
import { flare } from "@flareapp/flare-client";

// only launch in production, we don't want to waste your quota while you're developing.
if (process.env.NODE_ENV === "production") {
    flare.light("kcZlZkyL7nvdrT0RU7O3zIalPjCBmLVz");
}

Vue.mixin({ methods: { route: window.route } });
Vue.use(plugin);
Vue.use(flareVue);

const default_locale = window.default_language;
const fallback_locale = window.fallback_locale;
const messages = window.messages;

Vue.prototype.trans = new Lang({
    messages,
    locale: default_locale,
    fallback: fallback_locale
});

const app = document.getElementById("app");

new Vue({
    render: h =>
        h(App, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name =>
                    import(`@/Pages/${name}`).then(module => module.default)
            }
        }),
    store
}).$mount(app);
