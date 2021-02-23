<template>
    <table
        class="w-full mt-4 overflow-hidden divide-y divide-gray-200 rounded-md shadow"
    >
        <thead class="text-xs text-left text-gray-600 uppercase bg-gray-50">
            <tr>
                <th class="py-3 pl-5 font-medium tracking-wider" width="350">
                    Naam
                </th>
                <th
                    v-if="!hide.includes('relation_manager')"
                    class="font-medium tracking-wider"
                >
                    Relatiebeheerder
                </th>
                <th class="font-medium tracking-wider" width="250">
                    Contactpersoon
                </th>
                <th class="font-medium tracking-wider" width="250">
                    Status
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr
                v-for="(administration, index) in administrations"
                :key="administration.id"
            >
                <td class="py-4 pl-5 font-medium">
                    <inertia-link
                        :href="
                            route('administrations.show', {
                                id: administration.id
                            })
                        "
                    >
                        {{ administration.name }}
                    </inertia-link>
                </td>

                <td v-if="!hide.includes('relation_manager')">
                    {{ administration.relation_manager.first_name }}
                    {{ administration.relation_manager.last_name }}
                </td>

                <td>
                    {{ administration.contact_email }}
                </td>

                <td>
                    <div v-if="administration.last_overview_sent_at !== null">
                        <div class="text-xs text-gray-600">
                            Laatst verstuurd op
                        </div>
                        {{ administration.last_overview_sent_at }}
                    </div>
                    <div
                        v-else
                        class="inline-block px-3 py-1 text-xs font-semibold rounded-full cursor-pointer"
                        :class="statusClasses(administration.last_status.name)"
                        @click="$emit('showStatusInfo', administration)"
                    >
                        {{
                            trans.get(
                                "status." + administration.last_status.name
                            )
                        }}
                    </div>
                </td>
                <td class="p-3 py-3 pr-6 text-right" width="100">
                    <div
                        @click="emitSend(administration, index)"
                        :class="
                            canSend(administration)
                                ? 'cursor-pointer'
                                : 'opacity-50'
                        "
                    >
                        <div class="font-medium text-primary">
                            Verstuur
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        administrations: Array,
        hide: {
            type: Array,
            default: function() {
                return [];
            }
        }
    },

    methods: {
        hasLastOverview(administration) {
            if (administration.last_overview === null) {
                return false;
            }
            return true;
        },

        lastOverviewIsSent(administration) {
            if (!this.hasLastOverview(administration)) {
                return false;
            }
            if (administration.last_overview.sent_at === null) {
                return false;
            }

            return true;
        },

        emitSend(administration, index) {
            if (!this.canSend(administration)) {
                console.log("already submitting");
                return;
            }
            administration.submitting = true;
            this.$emit("sendOverview", {
                administration_id: administration.id,
                index: index
            });
        },

        canSend(administration) {
            if (administration.submitting !== undefined) {
                return false;
            }

            if (
                administration.last_status.name !== "new" &&
                administration.last_status.name !== "ready" &&
                administration.last_status.name !== "overview_failed"
            ) {
                return false;
            }

            return true;
        },

        statusClasses(status) {
            if (status === "overview_queued") {
                return "text-yellow-900 bg-yellow-200";
            }

            if (status === "overview_failed") {
                return "text-red-900 bg-red-200";
            }

            if (
                status === "overview_composing" ||
                status === "overview_sending"
            ) {
                return "text-blue-900 bg-blue-200 animate-pulse";
            }

            return "text-purple-900 bg-purple-200";
        }
    }
};
</script>
