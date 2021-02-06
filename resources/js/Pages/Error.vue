<template>
    <div class="flex items-center justify-center w-full h-full bg-gray-100">
        <div class="max-w-sm p-6 px-6 text-center">
            <h1 class="text-6xl font-black leading-none text-primary">
                {{ statusCode }}
            </h1>
            <div class="text-sm tracking-widest uppercase opacity-50">
                {{ title }}
            </div>
            <div class="mt-4">{{ description }}</div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        status: Number
    },
    computed: {
        title() {
            return {
                503: this.trans.get("response.503"),
                500: this.trans.get("response.500"),
                404: this.trans.get("response.404")
            }[this.status];
        },
        statusCode() {
            return {
                503: 503,
                500: 500,
                404: 404
            }[this.status];
        },
        description() {
            return {
                503: this.trans.get("response.503_message"),
                500: this.trans.get("response.500_message"),
                404: this.trans.get("response.404_message")
            }[this.status];
        }
    }
};
</script>
