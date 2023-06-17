// import { header } from '@/header';
// import { forms } from '@/forms';
// import { categorySlider } from '@/category-slider';
// import { scrollingText } from '@/scrolling-text';
// import { debounce } from '@/debounce';
//
// header();
// forms();
// categorySlider();
// scrollingText();
// debounce();

import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import List from '@editorjs/list';
import LinkTool from '@editorjs/link';

import { Tab } from "bootstrap";

editor();

export function editor() {
    const editorEl = document.querySelector('[data-editor]');
    if (editorEl) {
        const editor = new EditorJS({
            holder: editorEl,
            inlineToolbar: ['link', 'marker', 'bold', 'italic'],
            //inlineToolbar: true,
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



import '../../scss/admin/app.scss';
