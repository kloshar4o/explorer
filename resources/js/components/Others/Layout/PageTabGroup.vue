<template>
    <div class="page-tab-group">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'PageTabGroup',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';

    .page-tab-group {
        margin-bottom: 65px;
    }
</style>
