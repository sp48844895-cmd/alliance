<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    function initSummernoteEditors() {
        if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.summernote === 'undefined') {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        window.jQuery('[data-rich-editor]').each(function () {
            const $textarea = window.jQuery(this);
            if ($textarea.data('summernote-ready') === 1) {
                return;
            }

            $textarea.summernote({
                placeholder: 'Write Your Content Here',
                height: 400,
                tabsize: 2,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        Array.from(files || []).forEach(function (file) {
                            const formData = new FormData();
                            formData.append('upload', file);

                            window.jQuery.ajax({
                                url: '/author/stories/editor-image',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                success: function (response) {
                                    const imageUrl = response?.url || response?.default || response?.data?.url || null;
                                    if (!imageUrl) {
                                        alert('Image upload failed. Invalid server response.');
                                        return;
                                    }
                                    $textarea.summernote('insertImage', imageUrl);
                                },
                                error: function () {
                                    alert('Image upload failed. Please try again.');
                                }
                            });
                        });
                    }
                }
            });

            $textarea.data('summernote-ready', 1);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSummernoteEditors);
    } else {
        initSummernoteEditors();
    }
</script>
