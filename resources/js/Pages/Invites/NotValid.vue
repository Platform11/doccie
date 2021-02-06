<template>
    <div
        class="flex items-center justify-center min-h-screen p-6 bg-gray-100 dark:bg-black"
    >
        <flash-messages />
        <div class="w-full max-w-md">
            <logo class="w-48" />
            <div
                class="relative px-8 py-8 border-b border-gray-100 dark:border-gray-950"
            >
                <h2 class="text-2xl text-center">Helaas!</h2>
                <div v-if="reason == 'not valid'">
                    <p class="mt-2 text-center">
                        Deze uitnodiging is niet meer geldig. Het lijkt er op
                        dat je uitnodiging is ingetrokken.
                    </p>
                </div>
                <div v-if="reason == 'already accepted'">
                    <p class="mt-2 text-center">
                        Deze uitnodiging is al geacepteerd. Je kan proberen in
                        te loggen of als je je wachtwoord niet weet kan je hem
                        opnieuw instellen.
                    </p>
                    <div class="flex justify-center mt-4">
                        <inertia-link :href="route('login')">
                            <div class="mr-2 btn btn-primary">Inloggen</div>
                        </inertia-link>
                        <a href="/forgot-password">
                            <div class="btn btn-secondary">
                                Wachtwoord vergeten
                            </div>
                        </a>
                    </div>
                </div>
                <div v-if="reason == 'unknown'">
                    <p class="mt-2 text-center">
                        Deze uitnodiging is niet meer geldig. Neem contact op
                        met degene die je heeft uitgenodigd voor meer
                        informatie.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Logo from "@/Elements/Logo";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Logo
    },
    props: {
        reason: String
    },

    created() {
        this.pageTitle = "Helaas!";
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
