<template>
    <button
        @click="$emit('click')"
        :disabled="loading"
        class="relative inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md btn btn-primary ring-2 ring-opacity-0 ring-primary-light hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
    >
        <div v-if="loading" class="mr-2 btn-spinner" />
        <span :class="loading ? 'opacity-0' : 'opacity-100'"><slot /></span>
    </button>
</template>

<script>
export default {
    props: {
        loading: Boolean
    }
};
</script>
