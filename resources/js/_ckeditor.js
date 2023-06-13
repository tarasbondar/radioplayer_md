export function ckeditor() {
    if ($('.ckeditor-custom').length > 0) {
        InlineEditor.create(document.querySelector('.ckeditor-custom'))
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    }

}
