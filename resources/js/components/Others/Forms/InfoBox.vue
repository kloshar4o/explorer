<template>
    <div class="info-box" :class="type">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'InfoBox',
        props: ['type']
    }
</script>

<style lang="scss" scoped>
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';

    .info-box {
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 32px;
        background: $light_background;
        text-align: left;

        &.error {
            background: rgba($danger, 0.1);

            p, a {
                color: $danger;
            }

            a {
                text-decoration: underline;
            }
        }

        p {
            @include font-size(15);
            line-height: 1.6;
            word-break: break-word;
            font-weight: 600;

            /deep/ a {
                color: $theme;
            }
        }

        b {
            font-weight: 700;
            color: $theme;
        }

        a {
            color: $theme;
            font-weight: 700;
            @include font-size(15);
            line-height: 1.6;
        }

        ul {
            margin-top: 15px;
            display: block;

            li {
                display: block;

                a {
                    display: block;
                }
            }
        }
    }

    @media only screen and (max-width: 960px) {

    }

    @media only screen and (max-width: 690px) {

        .info-box {
            padding: 15px;
        }
    }


    @media (prefers-color-scheme: dark) {

        .info-box {
            background: $dark_mode_foreground;

            p {
                color: $dark_mode_text_primary;
            }

            ul {

                li {
                    color: $dark_mode_text_primary;
                }
            }
        }
    }
</style>
