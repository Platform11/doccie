<template>
    <div class="flex w-full h-full text-gray-800 standalone:h-map font-body">
        <header
            class="fixed z-20 w-full bg-white border-r border-gray-200 md:w-12 md:h-full xl:w-64 sm:block"
        >
            <div class="hidden md:block">
                <div
                    class="flex justify-center w-full pb-6 pt-7 xl:justify-start xl:pl-4 "
                >
                    <inertia-link href="/">
                        <Logo class="w-36" />
                    </inertia-link>
                </div>

                <div class="w-full py-1 mt-2 xl:pl-4 xl:pr-4">
                    <div
                        class="pl-4 mb-3 text-xs font-semibold tracking-wider text-gray-400 uppercase"
                    >
                        Klanten
                    </div>
                    <inertia-link href="/">
                        <div
                            class="flex items-center justify-center w-full xl:pl-2 xl:justify-start"
                            :class="
                                currentPageIsRelatedTo('')
                                    ? 'xl:rounded-lg bg-gray-100'
                                    : ''
                            "
                        >
                            <div
                                class="inline-block p-1 text-2xl xl:mr-1 jam jam-book"
                                :class="
                                    currentPageIsRelatedTo('')
                                        ? 'text-gray-700'
                                        : 'opacity-50'
                                "
                            ></div>
                            <span
                                class="hidden font-semibold xl:inline-block"
                                >{{
                                    trans.get("general.administrations")
                                }}</span
                            >
                        </div>
                    </inertia-link>

                    <div
                        class="pl-4 mt-8 mb-3 text-xs font-semibold tracking-wider text-gray-400 uppercase"
                    >
                        Kantoor
                    </div>
                    <inertia-link :href="route('users.index')">
                        <div
                            class="flex items-center justify-center w-full xl:pl-2 xl:justify-start"
                            :class="
                                currentPageIsRelatedTo('users')
                                    ? 'xl:rounded-lg bg-gray-100'
                                    : ''
                            "
                        >
                            <div
                                class="inline-block p-1 text-2xl xl:mr-1 jam jam-users"
                                :class="
                                    currentPageIsRelatedTo('users')
                                        ? 'text-gray-700'
                                        : 'opacity-50'
                                "
                            ></div>
                            <span
                                class="hidden font-semibold xl:inline-block"
                                >{{ trans.get("general.users") }}</span
                            >
                        </div>
                    </inertia-link>
                    <inertia-link :href="route('settings.index')">
                        <div
                            class="flex items-center justify-center w-full xl:pl-2 xl:justify-start"
                            :class="
                                currentPageIsRelatedTo('settings')
                                    ? 'xl:rounded-lg bg-gray-100'
                                    : ''
                            "
                        >
                            <div
                                class="inline-block p-1 text-2xl xl:mr-1 jam jam-settings-alt"
                                :class="
                                    currentPageIsRelatedTo('settings')
                                        ? 'text-gray-700'
                                        : 'opacity-50'
                                "
                            ></div>
                            <span
                                class="hidden font-semibold xl:inline-block"
                                >{{ trans.get("general.settings") }}</span
                            >
                        </div>
                    </inertia-link>
                </div>

                <div
                    class="absolute bottom-0 w-full mt-24 mb-6 lg:pl-4 lg:pr-4"
                >
                    <inertia-link class="w-full" :href="route('profile.show')">
                        <div
                            class="flex items-center justify-center w-full py-1 xl:pl-2 xl:justify-start xl:rounded-lg"
                        >
                            <span
                                class="inline-block p-1 text-2xl text-gray-800 opacity-50 xl:mr-1 jam jam-user-circle "
                            ></span>
                            <span class="hidden font-semibold xl:inline-block"
                                >{{ $page.props.auth.user.first_name }}
                                {{ $page.props.auth.user.last_name }}</span
                            >
                        </div>
                    </inertia-link>
                    <inertia-link
                        class="w-full"
                        href="/logout"
                        method="post"
                        as="button"
                    >
                        <div
                            class="flex items-center justify-center w-full py-1 xl:pl-2 xl:justify-start xl:rounded-lg"
                        >
                            <span
                                class="inline-block p-1 text-2xl text-gray-800 opacity-50 xl:mr-1 jam jam-door"
                            ></span>
                            <span
                                class="hidden font-semibold xl:inline-block"
                                >{{ trans.get("general.log_out") }}</span
                            >
                        </div>
                    </inertia-link>
                </div>
            </div>
        </header>

        <main
            class="w-full pb-4 text-gray-900 pt-11 standalone:pt-22 md:py-0 sm:py-11 md:pl-24 xl:pl-64"
        >
            <div class="w-full">
                <!-- <div class="w-full bg-white border-b border-gray-200">
                    <div class="relative z-30 px-4 py-3 mx-auto max-w-7xl">
                        <span
                            class="text-xl opacity-50 white jam jam-home-f"
                        ></span>
                    </div>
                </div> -->
                <div class="w-full">
                    <div class="px-4 pt-6 mx-auto max-w-7xl">
                        <slot @changePageTitle="setNewPageTitle($event)"></slot>
                    </div>
                </div>

                <flash-messages />

                <modal-alert />
            </div>
        </main>
    </div>
</template>

<script>
import FlashMessages from "@/Shared/FlashMessages";
import ModalAlert from "@/Components/Modals/Alert";
import Logo from "@/Elements/Logo";

export default {
    components: {
        FlashMessages,
        ModalAlert,
        Logo
    },
    data() {
        return {
            menuOpen: false
        };
    },

    created() {},

    methods: {
        setMenu() {},

        currentPageIsRelatedTo(slug) {
            return this.$page.props.url_prefix === slug;
        },

        setNewPageTitle(event) {
            console.log("Doccie");
        }
    },

    computed: {
        pageTitle: {
            set(pageTitle) {
                this.$store.commit("app/setPageTitle", pageTitle);
            },
            get() {
                return this.$store.state.app.pageTitle;
            }
        }
    }
};
</script>
