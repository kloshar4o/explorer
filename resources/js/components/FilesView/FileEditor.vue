<template>
    <div class="file-content file-content-editor">

        <div class="files-container"
             :class="{'is-fileinfo-visible': fileInfoVisible && !$isMinimalScale() }"
             @click.self="filesContainerClick">

            <!--MobileToolbar-->
            <MobileToolbar/>

            <!--Mobile Actions-->
            <MobileActions/>

            <MdFileEditor :path="fileInfoDetail.file_url"></MdFileEditor>
        </div>


    </div>
</template>

<script>
    import MobileToolbar from '@/components/FilesView/MobileToolbar'
    import MobileActions from '@/components/FilesView/MobileActions'
    import FileInfoPanel from '@/components/FilesView/FileInfoPanel'
    import FileItemList from '@/components/FilesView/FileItemList'
    import FileItemGrid from '@/components/FilesView/FileItemGrid'
    import EmptyMessage from '@/components/FilesView/EmptyMessage'
    import EmptyPage from '@/components/FilesView/EmptyPage'
    import SearchBar from '@/components/FilesView/SearchBar'
    import MdFileEditor from '@/components/FilesView/MdFileEditor'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FilesContainer',
        components: {
            MobileToolbar,
            MobileActions,
            FileInfoPanel,
            FileItemList,
            FileItemGrid,
            EmptyMessage,
            SearchBar,
            EmptyPage,
            MdFileEditor,
        },
        computed: {
            ...mapGetters([
                'uploadingFilesCount',
                'fileInfoVisible',
                'fileInfoDetail',
                'currentFolder',
                'FilePreviewType',
                'isSearching',
                'isLoading',
                'data'
            ])
        },
        data() {
            return {}
        },
        methods: {
            contextMenu(event, item) {
                events.$emit('contextMenu:show', event, item)
            },
            filesContainerClick() {
                events.$emit('contextMenu:hide')
            }
        },
        created() {
            this.$store.commit('FILE_INFO_TOGGLE', false)
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/explorer/_variables';
    @import '@assets/explorer/_mixins';

    .button-upload {
        display: block;
        text-align: center;
        margin: 20px 0;
    }

    .file-content {
        display: flex;

        &.is-dragging {
            @include transform(scale(0.99));
        }
    }

    .files-container {
        overflow-x: hidden;
        overflow-y: auto;
        flex: 0 0 100%;
        @include transition(150ms);
        position: relative;
        scroll-behavior: smooth;

        &.is-fileinfo-visible {
            flex: 0 1 100%;
        }

        .file-list {

            &.grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, 180px);
                justify-content: space-evenly;
            }
        }
    }

    .file-info-container {
        flex: 0 0 300px;
        padding-left: 20px;
        overflow: auto;
    }

    @media only screen and (min-width: 960px) {

        .file-content {
            position: absolute;
            top: 72px;
            left: 15px;
            right: 15px;
            bottom: 0;
            @include transition;
            overflow-y: auto;
            overflow-x: hidden;

            &.is-offset {
                margin-top: 50px;
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .file-info-container {
            display: none;
        }

        .mobile-search {
            display: block;
        }
    }

    @media only screen and (max-width: 690px) {

        .files-container {
            padding-left: 15px;
            padding-right: 15px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: fixed;
            overflow-y: auto;

            .file-list {

                &.grid {
                    grid-template-columns: repeat(auto-fill, 120px);
                }
            }
        }

        .file-content {
            position: absolute;
            top: 0;
            left: 0px;
            right: 0px;
            bottom: 0;
            @include transition;

            &.is-offset {
                margin-top: 50px;
            }
        }

        .mobile-search {
            margin-bottom: 0;
        }

        .file-info-container {
            display: none;
        }
    }
</style>
