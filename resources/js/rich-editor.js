function initRichEditors() {
    if (typeof ClassicEditor === 'undefined') {
        return;
    }

    document.querySelectorAll('[data-rich-editor]').forEach(textarea => {
        if (textarea.dataset.editorReady === '1') {
            return;
        }

        const form = textarea.closest('form');
        let editorInstance = null;

        ClassicEditor.create(textarea, {
            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                '|',
                'bulletedList',
                'numberedList',
                'blockQuote',
                '|',
                'undo',
                'redo',
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Subheading', class: 'ck-heading_heading3' },
                ],
            },
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
            },
        })
            .then(editor => {
                textarea.dataset.editorReady = '1';
                editorInstance = editor;
                const editable = editor.ui.view.editable.element;
                if (editable) {
                    editable.classList.add('rich-editor-editable');
                }
            })
            .catch(error => {
                console.error('CKEditor failed to load:', error);
            });

        if (form) {
            form.addEventListener('submit', () => {
                if (editorInstance) {
                    textarea.value = editorInstance.getData();
                }
            });
        }
    });
}

function bootRichEditors() {
    if (typeof ClassicEditor === 'undefined') {
        window.setTimeout(bootRichEditors, 50);
        return;
    }
    initRichEditors();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootRichEditors);
} else {
    bootRichEditors();
}
