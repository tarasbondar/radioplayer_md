import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from '@editorjs/list';
import LinkTool from '@editorjs/link';


export function editor() {
    const editorEl = document.querySelector('[data-editor]');
    if (editorEl) {
        const editor = new EditorJS({
            /**
             * Id of Element that should contain Editor instance
             */
            holder: editorEl,

            inlineToolbar: ['link', 'marker', 'bold', 'italic'],
            inlineToolbar: true,

            placeholder: 'Описание подкаста',

            tools: {
                header: Header,
                list: {
                    class: List,
                    inlineToolbar: true,
                    config: {
                        defaultStyle: 'unordered'
                    }
                },
                linkTool: {
                    class: LinkTool,
                    config: {
                        endpoint: 'http://localhost:8008/fetchUrl', // Your backend endpoint for url data fetching,
                    }
                }
            },
        });
    }
}
