<template>
    <editor-content class="editor__content " v-if="editor" :editor="editor"/>
</template>

<script>
    // Import the editor

    import javascript from 'highlight.js/lib/languages/javascript'
    import css from 'highlight.js/lib/languages/css'
    import {Editor, EditorContent, EditorMenuBar} from 'tiptap'
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
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
        CodeBlockHighlight,
        HorizontalRule,
    } from 'tiptap-extensions'

    export default {
        name: 'MdFilePreview',
        props: ['basename'],
        components: {
            EditorContent,
        },
        data() {
            return {
                editor: null,
            }
        },
        mounted() {
            fetch("/api/file/" + this.basename)
                .then(x => x.text())
                .then(text => {
                    this.editor = new Editor({
                        editable: false,
                        content: text,
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
                    })
                })

        },
        beforeDestroy() {
            if (this.editor)
                this.editor.destroy()
        },
    }
</script>

