<template>
    <div class="file-wrapper">
        <!--List preview-->
        <div class="file-item">
            <!--Thumbnail for item-->
            <div class="icon-item">

                <!--If is file or image, then link item-->
                <span v-if="isFile" class="file-icon-text">
					{{ data.mimetype | limitCharacters }}
				</span>

                <!--Folder thumbnail-->
                <FontAwesomeIcon v-if="isFile" class="file-icon" icon="file"/>

                <FontAwesomeIcon v-if="isImage && !user" class="file-icon" icon="file-image"/>

                <!--Image thumbnail-->
                <img v-if="isImage && user" class="image" :src="data.thumbnail" :alt="data.name"/>

                <!--Else show only folder icon-->
                <FontAwesomeIcon v-if="isFolder" class="folder-icon" icon="folder"/>
            </div>

            <!--Name-->
            <div class="item-name">
                <!--Name-->
                <b ref="name" class="name">{{ itemName }}</b>

                <div class="item-info">
                    <div>
                        <!--Shared Icon-->
                        <span class="item-shared">
                            <link-icon size="12" class="shared-icon"></link-icon>
                        </span>

                        <!-- Timestamp -->
                        <span v-if="!isFolder" class="item-size">{{ timeStamp }}</span>

                        <!--Folder item counts-->
                        <span v-if="isFolder" class="item-length">
                            {{ timeStamp }} </span>
                    </div>

                    <!--Filesize and timestamp-->
                    <div class="item-shared">
                        <span class="item-size">
                            {{ folderItems == 0 ? $t('folder.empty') : $tc('folder.item_counts', folderItems)}}
                            {{ data.filesize }}
                        </span>
                    </div>
                </div>
            </div>

            <!--Go Next icon-->
            <div class="actions">

                <CopyInputMulti
                        v-if="user"
                        class="copy-sharelink"
                        size="small"
                        :value="data.shared.link"
                        :unique_id="data.unique_id"
                />

                <div v-else class="download-login">
                    <div>
                        <CopyInputMulti
                                size="small"
                                class="copy-sharelink"
                        ></CopyInputMulti>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {LinkIcon, UserPlusIcon} from 'vue-feather-icons'
    import {debounce} from 'lodash'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    import CopyInputMulti from '@/components/Others/Forms/CopyInputMulti'


    export default {
        name: 'FileItemListPublic',
        props: ['data'],
        components: {
            UserPlusIcon,
            LinkIcon,
            CopyInputMulti
        },
        computed: {
            ...mapGetters(['FilePreviewType', 'user']),
            isFolder() {
                return this.data.type === 'folder'
            },
            isFile() {
                return this.data.type !== 'folder' && this.data.type !== 'image'
            },
            isImage() {
                return this.data.type === 'image'
            },
            isPdf() {
                return this.data.mimetype === 'pdf'
            },
            isVideo() {
                return this.data.type === 'video'
            },
            isAudio() {
                let mimetypes = ['mpeg', 'mp3', 'mp4', 'wan', 'flac']
                return mimetypes.includes(this.data.mimetype) && this.data.type === 'audio'
            },
            timeStamp() {
                return this.data.deleted_at ? this.$t('item_thumbnail.deleted_at', {time: this.data.deleted_at}) : this.data.created_at
            },
            folderItems() {
                return this.data.deleted_at ? this.data.trashed_items : this.data.items
            },
        },
        filters: {
            limitCharacters(str) {
                if (str && str.length > 3) {
                    return str.substring(0, 3) + '...'
                } else {
                    return str.substring(0, 3)
                }
            }
        },
        data() {
            return {
                isClicked: false,
                area: false,
                itemName: undefined
            }
        },
        methods: {},
        created() {
            this.itemName = this.data.name
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';

    .download-login {
        white-space: nowrap;
    }

    .file-wrapper {
        user-select: none;
        position: relative;

        &:hover {
            border-color: transparent;
        }

        .actions {
            text-align: right;

            .show-actions {
                cursor: pointer;
                padding: 12px 6px 12px;

                .icon-action {
                    @include font-size(14);

                    path {
                        fill: $theme;
                    }
                }
            }
        }

        .item-name {
            display: block;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

            .item-info {
                display: block;
                line-height: 1;
            }

            .item-shared {
                display: inline-block;

                .label {
                    @include font-size(12);
                    font-weight: 400;
                    color: $theme;
                }

                .shared-icon {
                    vertical-align: middle;

                    path,
                    circle,
                    line {
                        stroke: $theme;
                    }
                }
            }

            .item-size,
            .item-length {
                @include font-size(11);
                font-weight: 400;
                color: rgba($text, 0.7);
            }

            .name {
                white-space: nowrap;

                &[contenteditable] {
                    -webkit-user-select: text;
                    user-select: text;
                }

                &[contenteditable='true']:hover {
                    text-decoration: underline;
                }
            }

            .name {
                color: $text;
                @include font-size(14);
                font-weight: 700;
                max-height: 40px;
                overflow: hidden;
                text-overflow: ellipsis;

                &.actived {
                    max-height: initial;
                }
            }
        }

        &.selected {
            .file-item {
                background: $light_background;
            }
        }

        .icon-item {
            text-align: center;
            position: relative;
            flex: 0 0 50px;
            line-height: 0;
            margin-right: 20px;

            .folder-icon {
                @include font-size(52);

                path {
                    fill: $theme;
                }

                &.is-deleted {
                    path {
                        fill: $dark_background;
                    }
                }
            }

            .file-icon {
                @include font-size(45);

                path {
                    fill: #fafafc;
                    stroke: #dfe0e8;
                    stroke-width: 1;
                }
            }

            .file-icon-text {
                line-height: 1;
                top: 40%;
                @include font-size(11);
                margin: 0 auto;
                position: absolute;
                text-align: center;
                left: 0;
                right: 0;
                color: $theme;
                font-weight: 600;
                user-select: none;
                max-width: 50px;
                max-height: 20px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .image {
                object-fit: cover;
                user-select: none;
                max-width: 100%;
                border-radius: 5px;
                width: 50px;
                height: 50px;
                pointer-events: none;
            }
        }

        .file-item {
            border: 2px dashed transparent;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 7px;

            &.is-dragenter {
                border: 2px dashed $theme;
                border-radius: 8px;
            }

            &:hover,
            &.is-clicked {
                border-radius: 8px;
                background: $light_background;

                .item-name .name {
                    color: $theme;
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .file-wrapper {
            .icon-item {
                .file-icon {
                    path {
                        fill: $dark_mode_foreground;
                        stroke: #2f3c54;
                    }
                }

                .folder-icon {
                    &.is-deleted {
                        path {
                            fill: lighten($dark_mode_foreground, 5%);
                        }
                    }
                }
            }

            .file-item {
                &:hover,
                &.is-clicked {
                    background: $dark_mode_foreground;

                    .file-icon {
                        path {
                            fill: $dark_mode_background;
                        }
                    }
                }
            }

            .item-name {
                .name {
                    color: $dark_mode_text_primary;
                }

                .item-size,
                .item-length {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }
</style>