<template>
    <div class="page-tab">
        <div id="loader" v-show="isLoading">
            <Spinner></Spinner>
        </div>
        <slot v-show="! isLoading"></slot>
    </div>
</template>

<script>
    import Spinner from '@/components/FilesView/Spinner'

    export default {
        name: 'PageTab',
        props: ['isLoading'],
        components: {
            Spinner,
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';
</style>