<style>
    @import url("https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css");

    .note-editor.note-frame {
        border: 1px solid var(--color-line);
        border-radius: 0.5rem;
        overflow: hidden;
        background: var(--color-surface);
    }
    .note-editor.note-frame .note-toolbar {
        background: var(--color-paper);
        border-bottom: 1px solid var(--color-line);
    }
    .note-editor.note-frame .note-editing-area .note-editable {
        min-height: 280px;
        font-family: Roboto, system-ui, sans-serif;
        font-size: 0.9375rem;
        line-height: 1.6;
        color: var(--color-ink-2);
        background: var(--color-surface);
    }
    .note-editor.note-frame .note-editing-area .note-editable:focus {
        outline: none;
    }
    .story-html-content h2,
    .story-html-content h3 {
        font-family: var(--font-display);
        color: var(--color-ink-2);
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .story-html-content p {
        margin-bottom: 1rem;
        line-height: 1.65;
    }
    .story-html-content ul,
    .story-html-content ol {
        margin: 0 0 1rem 1.25rem;
    }
    .story-html-content blockquote {
        border-left: 3px solid var(--color-clay-400);
        padding-left: 1rem;
        color: var(--color-mute);
        font-style: italic;
    }
    .story-html-content a {
        color: var(--color-clay-600);
        text-decoration: underline;
    }
</style>
