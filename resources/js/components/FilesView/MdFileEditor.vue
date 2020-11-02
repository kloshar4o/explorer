<template>
    <div class="editor can-edit">
        <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
            <div class="menubar">

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.bold() }"
                        @click="commands.bold"
                >
                    <icon name="bold"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.italic() }"
                        @click="commands.italic"
                >
                    <icon name="italic"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.strike() }"
                        @click="commands.strike"
                >
                    <icon name="strike"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.underline() }"
                        @click="commands.underline"
                >
                    <icon name="underline"/>
                </button>


                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.paragraph() }"
                        @click="commands.paragraph"
                >
                    <icon name="paragraph"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                        @click="commands.heading({ level: 1 })"
                >
                    H1
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                        @click="commands.heading({ level: 2 })"
                >
                    H2
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                        @click="commands.heading({ level: 3 })"
                >
                    H3
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.bullet_list() }"
                        @click="commands.bullet_list"
                >
                    <icon name="ul"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.ordered_list() }"
                        @click="commands.ordered_list"
                >
                    <icon name="ol"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.blockquote() }"
                        @click="commands.blockquote"
                >
                    <icon name="quote"/>
                </button>

                <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.code_block() }"
                        @click="commands.code_block"
                >
                    <icon name="code"/>
                </button>

                <button
                        class="menubar__button"
                        @click="commands.horizontal_rule"
                >
                    <icon name="hr"/>
                </button>

                <button
                        class="menubar__button"
                        @click="commands.undo"
                >
                    <icon name="undo"/>
                </button>

                <button
                        class="menubar__button"
                        @click="commands.redo"
                >
                    <icon name="redo"/>
                </button>

            </div>
        </editor-menu-bar>

        <editor-content class="editor__content" :editor="editor"/>
    </div>
</template>

<script>

    import javascript from 'highlight.js/lib/languages/javascript'
    import css from 'highlight.js/lib/languages/css'
    import axios from 'axios'
    import Icon from '@/components/FilesView/Icon'
    import {events} from "@/bus";
    import {mapGetters} from 'vuex'
    import {Editor, EditorContent, EditorMenuBar} from 'tiptap'
    import {
        CodeBlockHighlight,
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        HorizontalRule,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
    } from 'tiptap-extensions'

    export default {
        computed: {

            ...mapGetters([
                'editorContent',
                'fileInfoDetail',
            ]),
        },
        components: {
            EditorContent,
            EditorMenuBar,
            Icon,
        },
        methods: {
            saveFile() {
                const content = this.editor.getHTML();
                const {id} = this.fileInfoDetail;


                axios.post('/api/save-md-file', {content, id})
                    .then((response) => {

                        const {success, message} = response.data;
                        const type = success ? 'success' : 'danger';

                        events.$emit('toaster', {type, message})
                    })
                    .catch(err => console.log(err))

            }
        },
        data() {
            return {
                editor: new Editor({
                    extensions: [
                        new CodeBlockHighlight({
                            languages: {
                                javascript,
                                css,
                            },
                        }),
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({levels: [1, 2, 3]}),
                        new HorizontalRule(),
                        new ListItem(),
                        new OrderedList(),
                        new TodoItem(),
                        new TodoList(),
                        new Link(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Strike(),
                        new Underline(),
                        new History(),
                    ],
                    content: '',
                }),
            }
        },
        mounted() {
            this.editor.setContent(this.editorContent)
            events.$on("saveEditor", _ => this.saveFile());
        },
        beforeDestroy() {
            events.$off('saveEditor');
            this.editor.destroy()
        },
    }
</script>

<style lang="scss">

    .editor.can-edit .editor__content{
        border: solid 3px rgba(150,150,150,.5);
        border-radius: 10px;
        padding: 10px;
    }
    .menubar__button.is-active {
        background-color: rgba(150,150,150,.3);
    }
    pre {
        &::before {
            content: attr(data-language);
            text-transform: uppercase;
            display: block;
            text-align: right;
            font-weight: bold;
            font-size: 0.6rem;
        }
        code {
            .hljs-comment,
            .hljs-quote {
                color: #999999;
            }
            .hljs-variable,
            .hljs-template-variable,
            .hljs-attribute,
            .hljs-tag,
            .hljs-name,
            .hljs-regexp,
            .hljs-link,
            .hljs-name,
            .hljs-selector-id,
            .hljs-selector-class {
                color: #f2777a;
            }
            .hljs-number,
            .hljs-meta,
            .hljs-built_in,
            .hljs-builtin-name,
            .hljs-literal,
            .hljs-type,
            .hljs-params {
                color: #f99157;
            }
            .hljs-string,
            .hljs-symbol,
            .hljs-bullet {
                color: #99cc99;
            }
            .hljs-title,
            .hljs-section {
                color: #ffcc66;
            }
            .hljs-keyword,
            .hljs-selector-tag {
                color: #6699cc;
            }
            .hljs-emphasis {
                font-style: italic;
            }
            .hljs-strong {
                font-weight: 700;
            }
        }
    }
</style>