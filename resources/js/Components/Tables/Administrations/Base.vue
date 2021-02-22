<template>
    <div>
        <div v-if="administrations.length == 0" class="font-medium opacity-75 ">
            Er zijn nog geen administraties gekoppeld deze gebruiker
        </div>
        <Table
            v-if="administrations.length > 0"
            :administrations="administrations"
            :key="tableKey"
            @sendOverview="sendOverview"
            @showStatusInfo="showStatusInfo"
        />
        <modal-info
            :show="infoModal.show"
            :type="infoModal.type"
            :title="infoModal.title"
            :message="infoModal.message"
            :confirm_text="infoModal.confirm_text"
            @close="infoModal.show = false"
        />
    </div>
</template>

<script>
import ModalInfo from "@/Components/Modals/Info";
import Table from "@/Components/Tables/Administrations/Table";

export default {
    components: {
        ModalInfo,
        Table
    },

    props: {
        administrations: Array
    },

    mounted() {
        Echo.private("account." + this.$page.props.auth.user.account_id).listen(
            ".administration.status.updated",
            e => {
                this.handleEvent(e);
            }
        );
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
                confirm_text: ""
            }
        };
    },

    methods: {
        handleEvent(event) {
            let index = this.administrations.findIndex(
                administration => administration.id == event.administration.id
            );
            if (index > -1) {
                this.administrations[index] = event.administration;
                this.tableKey++;
            }
        },

        sendOverview(data) {
            this.tableKey++;

            axios({
                method: "get",
                url: this.route("administrations.send-overview", {
                    administration: data.administration_id
                })
            }).then(response => {
                this.submitting = false;
                this.tableKey++;
            });
        },

        showStatusInfo(administration) {
            this.infoModal.type = "info";

            if (administration.last_status.name == "overview_failed") {
                this.infoModal.type = "error";
            }

            let reason = administration.last_status.reason.split("::");

            this.infoModal.title = reason[0];
            this.infoModal.message = reason[1];
            this.infoModal.confirm_text = "Ik begrijp het";
            this.infoModal.show = true;
        }
    }
};
</script>
