<template>
    <div>
        <div v-if="overviews.length == 0" class="font-medium opacity-75 ">
            Er zijn nog geen overzichten verstuurd voor deze administratie.
        </div>
        <Table
            v-if="overviews.length > 0"
            :overviews="overviews"
            :key="tableKey"
            @showStatusInfo="showStatusInfo"
        />
        <modal-info
            :show="infoModal.show"
            :type="infoModal.type"
            :title="infoModal.title"
            :message="infoModal.message"
            :confirm_text="infoModal.confirm_text"
            :additional-html="infoModal.additionalHtml"
            @close="infoModal.show = false"
        />
    </div>
</template>

<script>
import ModalInfo from "@/Components/Modals/Info";
import Table from "@/Components/Tables/Overviews/Table";

export default {
    components: {
        ModalInfo,
        Table
    },

    props: {
        overviews: Array,
        administrationId: Number
    },

    mounted() {
        Echo.private("administration." + this.administrationId)
            .listen(".overview.status.updated", e => {
                this.handleOverviewEvent(e);
            })
            .listen(".notification.status.updated", e => {
                this.handleNotificationEvent(e);
            });
    },

    data() {
        return {
            submitting: false,
            tableKey: 1,
            infoModal: {
                show: false,
                type: "",
                title: "",
                message: "",
                confirm_text: "",
                additionalHtml: ""
            }
        };
    },

    methods: {
        handleOverviewEvent(event) {
            let index = this.overviews.findIndex(
                overview => overview.id == event.overview.id
            );
            if (index > -1) {
                this.overviews[index] = event.overview;
                this.tableKey++;
            }
        },

        handleNotificationEvent(event) {
            let index = this.overviews.findIndex(
                overview => overview.id == event.notification.subject_id
            );
            if (index > -1) {
                let notificationIndex = this.overviews[
                    index
                ].notifications.findIndex(
                    notification => notification.id == event.notification.id
                );
                if (notificationIndex > -1) {
                    this.overviews[index].notifications[notificationIndex] =
                        event.notification;
                    this.tableKey++;
                }
            }
        },

        showStatusInfo(overview) {
            this.infoModal.type = "info";
            this.infoModal.additionalHtml = "";

            if (overview.last_status.name == "failed") {
                this.infoModal.type = "error";
            }

            if (overview.last_status.name == "sending_failed") {
                this.infoModal.type = "error";
                this.infoModal.additionalHtml = this.generateSendInfoHTML(
                    overview
                );
            }

            if (overview.last_status.name == "sent") {
                this.infoModal.type = "info";
                this.infoModal.additionalHtml = this.generateSendInfoHTML(
                    overview
                );
            }

            if (overview.last_status.name == "delivered") {
                this.infoModal.type = "success";
                this.infoModal.additionalHtml = this.generateSendInfoHTML(
                    overview
                );
            }

            let reason = overview.last_status.reason.split("::");

            this.infoModal.title = reason[0];
            this.infoModal.message = reason[1];
            this.infoModal.confirm_text = "Ik begrijp het";
            this.infoModal.show = true;
        },

        generateSendInfoHTML(overview) {
            let html = '<table class="w-full text-left text-gray-600">';

            overview.notifications.forEach(function(element) {
                let iconColor = "text-gray-400";

                if (element.last_status.name == "delivered") {
                    iconColor = "text-green-600";
                }

                if (element.last_status.name == "bounced") {
                    iconColor = "text-red-500";
                }

                html += "<tr>";
                html += "<td>";
                html += "<div class='py-1'>";
                html += element.recipient;
                html += "</div>";
                html += "</td>";

                if (element.last_status.name == "delivered") {
                    html +=
                        "<td class='text-xs font-medium text-right text-green-600'>Afgeleverd</td>";
                } else if (element.last_status.name == "bounced") {
                    html +=
                        "<td class='text-xs font-medium text-right text-red-600'>MisluktR</td>";
                } else {
                    html +=
                        "<td class='text-xs font-medium text-right text-gray-400'>Onderweg</td>";
                }

                html += "</td>";
            });

            html += "</table>";

            return html;
        }
    }
};
</script>
