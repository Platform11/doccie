<template>
    <table
        class="w-full mt-4 overflow-hidden divide-y divide-gray-200 rounded-md shadow"
    >
        <thead class="text-xs text-left text-gray-600 uppercase bg-gray-50">
            <tr>
                <th class="p-3 pl-4 font-medium tracking-wider">
                    Rapporten
                </th>
                <th class="font-medium tracking-wider" width="225">
                    Status
                </th>
                <th class="font-medium tracking-wider">
                    Aangemaakt op
                </th>
                <th class="font-medium tracking-wider">
                    Verstuurd op
                </th>
                <th class="font-medium tracking-wider">
                    Door
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="overview in overviews" :key="overview.id">
                <td class="p-3 py-4 pl-4">
                    {{ overview.content_title }}
                </td>

                <td>
                    <div
                        class="inline-block px-3 py-1 text-xs font-semibold rounded-full cursor-pointer"
                        :class="statusClasses(overview.last_status.name)"
                        @click="$emit('showStatusInfo', overview)"
                    >
                        {{ trans.get("status." + overview.last_status.name) }}
                    </div>
                </td>

                <td>
                    {{ overview.created_at }}
                </td>
                <td>
                    {{ overview.sent_at }}
                </td>
                <td>
                    {{ overview.author.first_name }}
                    {{ overview.author.last_name }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        overviews: Array,
        hide: {
            type: Array,
            default: function() {
                return [];
            }
        }
    },

    methods: {
        statusClasses(status) {
            if (status === "queued") {
                return "text-yellow-900 bg-yellow-200";
            }

            if (status === "failed") {
                return "text-red-900 bg-red-200";
            }

            if (status === "delivered") {
                return "text-green-900 bg-green-200";
            }

            if (
                status === "composing" ||
                status === "sending" ||
                status === "sent"
            ) {
                if (status === "sent") {
                    return "text-blue-900 bg-blue-200";
                }
                return "text-blue-900 bg-blue-200 animate-pulse";
            }

            return "text-purple-900 bg-purple-200";
        }
    }
};
</script>
