<template>
    <div id="mobile-actions-wrapper">

        <div v-if="showEditor" style="display: flex; justify-content: space-between; margin: 20px 0">

            <MobileActionButton icon="save" @click.native="save">
                {{ $t('actions.save') }}
            </MobileActionButton>
            <MobileActionButton icon="close" @click.native="goBack" style="margin-right: 0">
                {{ $t('actions.close') }}
            </MobileActionButton>
        </div>

        <div v-else>

            <!--Actions for trash location with MASTER permission--->
            <div v-if="$isThisLocation(['trash', 'trash-root']) && $checkPermission('master')" class="mobile-actions">
                <MobileActionButton @click.native="switchPreview" :icon="previewIcon">
                    {{ previewText }}
                </MobileActionButton>
                <MobileActionButton @click.native="$store.dispatch('emptyTrash')" icon="trash">
                    {{ $t('context_menu.empty_trash') }}
                </MobileActionButton>
            </div>

            <!--ContextMenu for Base location with MASTER permission-->
            <div v-if="$isThisLocation(['base', 'public']) && $checkPermission(['master', 'editor'])" class="mobile-actions">

                <MobileActionButton icon="file-plus" @click.native="createMdFile">
                    {{ $t('actions.add_md') }}
                </MobileActionButton>

                <MobileActionButton @click.native="createFolder" icon="folder-plus">
                    {{ $t('context_menu.add_folder') }}
                </MobileActionButton>
                <br>
                <br>
                <MobileActionButtonUpload>
                    {{ $t('context_menu.upload') }}
                </MobileActionButtonUpload>
                <MobileActionButton @click.native="switchPreview" :icon="previewIcon">
                    {{ previewText }}
                </MobileActionButton>
            </div>

            <!--ContextMenu for Base location with VISITOR permission-->
            <div v-if="($isThisLocation(['base', 'shared', 'public']) && $checkPermission('visitor')) || ($isThisLocation(['latest', 'shared']) && $checkPermission('master'))" class="mobile-actions">
                <MobileActionButton @click.native="switchPreview" :icon="previewIcon">
                    {{ previewText }}
                </MobileActionButton>
            </div>

        </div>
        <!--Upload Progressbar-->
        <UploadProgress />
    </div>
</template>

<script>
    import MobileActionButtonUpload from '@/components/FilesView/MobileActionButtonUpload'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import UploadProgress from '@/components/FilesView/UploadProgress'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'
    import {last} from "lodash";

    export default {
        name: 'MobileActions',
        components: {
            MobileActionButtonUpload,
            MobileActionButton,
            UploadProgress,
        },
        computed: {
            ...mapGetters([
                'FilePreviewType',
                "browseHistory",
                'showEditor'
            ]),
            previewIcon() {
                return this.FilePreviewType === 'list' ? 'th' : 'th-list'
            },
            previewText() {
                return this.FilePreviewType === 'list' ? this.$t('preview_type.grid') : this.$t('preview_type.list')
            }
        },
        methods: {

            save(){
                events.$emit('saveEditor')
            },
            goBack() {
                // Get previous folder
                let previousFolder = last(this.browseHistory);

                if (!previousFolder) return;

                if (previousFolder.location === "trash-root") {
                    this.$store.dispatch("getTrash");
                } else if (previousFolder.location === "shared") {
                    this.$store.dispatch("getShared");
                } else {
                    if (this.$isThisLocation("public")) {
                        this.$store.dispatch("browseShared", [
                            {folder: previousFolder, back: true, init: false},
                        ]);
                    } else {
                        this.$store.dispatch("getFolder", [
                            {folder: previousFolder, back: true, init: false},
                        ]);
                    }
                }
            },
            switchPreview() {
                this.$store.dispatch('changePreviewType')
            },
            createMdFile() {
                this.$createMdFile();
            },
            createFolder() {
                if (this.$isMobile()) {
                    // Get folder name
                    let folderName = prompt(this.$t('popup_create_folder.title'))

                    // Create folder
                    if (folderName) this.$createFolder(folderName)
                } else {
                    // Create folder
                    this.$createFolder(this.$t('popup_create_folder.folder_default_name'))
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';

    #mobile-actions-wrapper {
        display: none;
        background: white;
        position: sticky;
        top: 35px;
        z-index: 3;
    }

    .mobile-actions {
        padding-top: 10px;
        padding-bottom: 10px;
        white-space: nowrap;
        overflow-x: auto;
    }

    @media only screen and (max-width: 960px) {

        #mobile-actions-wrapper {
            display: block;
        }
    }

    @media (prefers-color-scheme: dark) {
        #mobile-actions-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
