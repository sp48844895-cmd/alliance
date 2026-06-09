function createAuthorUploadAdapter(loader) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const toBase64 = file => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = () => reject(new Error('Image upload failed.'));
        reader.readAsDataURL(file);
    });

    return {
        upload: () => loader.file.then(file => new Promise((resolve, reject) => {
            const data = new FormData();
            data.append('upload', file);

            fetch('/author/stories/editor-image', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: data,
            })
                .then(async response => {
                    const payload = await response.json().catch(() => null);

                    if (!response.ok) {
                        const message = payload?.message || payload?.error?.message || 'Image upload failed.';
                        reject(message);
                        return;
                    }

                    const uploadedUrl = payload?.url || payload?.default || payload?.data?.url || null;

                    if (uploadedUrl) {
                        resolve({ default: uploadedUrl });
                        return;
                    }

                    const localDataUrl = await toBase64(file).catch(() => null);
                    if (localDataUrl) {
                        resolve({ default: localDataUrl });
                        return;
                    }

                    reject('Image upload response is invalid.');
                })
                .catch(async () => {
                    const localDataUrl = await toBase64(file).catch(() => null);
                    if (localDataUrl) {
                        resolve({ default: localDataUrl });
                        return;
                    }
                    reject('Image upload failed.');
                });
        })),
        abort: () => {},
    };
}

function authorUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = loader => createAuthorUploadAdapter(loader);
}

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
            extraPlugins: [authorUploadAdapterPlugin],
            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'uploadImage',
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
            image: {
                toolbar: ['imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'],
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
