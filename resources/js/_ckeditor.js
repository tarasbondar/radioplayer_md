export function ckeditor() {
    if ($('.ckeditor-custom').length > 0) {
        InlineEditor.create(document.querySelector('.ckeditor-custom'))
            .then(editor => {
                window.editor = editor;
                $(document).on('submit', '#podcast-form, #apply-form', function () {
                    const content = editor.getData();
                    $('#description').val(content);
                });
            })
            .catch(error => {
                console.error(error);
            });
    }
}
