<template>
    <div v-if="unique_id" class="inline-wrapper icon-append copy-input" :class="size" @click="copyUrl(unique_id)">
        <input ref="sel" :value="value" :id="unique_id" type="text" class="input-text" readonly>
        <div class="icon">
            <link-icon v-if="! isCopiedLink" size="14"></link-icon>
            <check-icon v-if="isCopiedLink" size="14"></check-icon>
        </div>
    </div>

    <div v-else="" class="inline-wrapper icon-append copy-input" :class="size" @click="$router.push({name: 'SignIn'})">
        <input ref="sel" :value="$t('explore.show_link')" type="text" class="input-text" readonly>
        <div class="icon">
            <link-icon v-if="!isCopiedLink" size="14"></link-icon>
            <check-icon v-if="isCopiedLink" size="14"></check-icon>
        </div>
    </div>
</template>

<script>
    import { LinkIcon, CheckIcon } from 'vue-feather-icons'

    export default {
        name: 'CopyInputMulti',
        props: ['size', 'value', 'unique_id'],
        components: {
            CheckIcon,
            LinkIcon,
        },
        data() {
            return {
                isCopiedLink: false,
            }
        },
        methods: {
            copyUrl(unique_id) {

                // Get input value
                const copyText = document.getElementById(unique_id);

                // select link
                copyText.select();
                copyText.setSelectionRange(0, 99999);

                // Copy
                document.execCommand("copy");

                // Mark button as copied
                this.isCopiedLink = true

                // Reset copy button
                setTimeout(() => {this.isCopiedLink = false}, 1000)
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';
    @import "@assets/explorer/_inapp-forms.scss";
    @import "@assets/explorer/_forms.scss";

    .input-text {
        width: inherit !important;
    }
    // Single page
    .copy-input {

        &.small {

            &.icon-append {

                .icon {
                    padding: 10px;
                }
            }

            input {
                padding: 6px 10px;
                @include font-size(13);
            }
        }

        .icon {
            cursor: pointer;
        }

        input {
            text-overflow: ellipsis;

            &:disabled {
                color: $text;
                cursor: pointer;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .copy-input {
            input {
                color: $dark_mode_text_primary;
            }
        }
    }
</style>
