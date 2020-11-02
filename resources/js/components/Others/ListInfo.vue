<template>
    <ul class="list-info">
        <slot></slot>
    </ul>
</template>

<script>
    export default {
        name: 'ListInfo',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';
</style>
