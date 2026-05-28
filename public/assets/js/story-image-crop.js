(function () {
    document.querySelectorAll('[data-story-image-crop]').forEach((root) => {
        const imageInput = root.querySelector('[data-story-image-input]');
        const imagePreview = root.querySelector('[data-story-image-preview]');
        const cropPanel = root.querySelector('[data-story-crop-panel]');
        const processBtn = root.querySelector('[data-story-crop-apply]');
        const finalOutput = root.querySelector('[data-story-image-result]');
        const resultWrap = root.querySelector('[data-story-image-result-wrap]');
        const statusEl = root.querySelector('[data-story-crop-status]');
        const form = root.closest('form');

        const aspectRatio = parseFloat(root.dataset.aspectRatio || '1.7777777778');
        const maxSizeMb = parseFloat(root.dataset.maxSizeMb || '0.1');
        const maxWidthOrHeight = parseInt(root.dataset.maxDimension || '1024', 10);

        let cropper = null;
        let readyFile = null;

        const setStatus = (message, isError) => {
            if (!statusEl) return;
            statusEl.textContent = message;
            statusEl.classList.toggle('text-[var(--color-flame)]', Boolean(isError));
            statusEl.classList.toggle('text-[var(--color-mute)]', !isError);
        };

        const resetCropper = () => {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        };

        const showCropPanel = (show) => {
            cropPanel?.classList.toggle('hidden', !show);
            processBtn?.classList.toggle('hidden', !show);
        };

        imageInput?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            readyFile = null;
            resultWrap?.classList.add('hidden');
            finalOutput?.removeAttribute('src');

            if (!file) {
                resetCropper();
                showCropPanel(false);
                imagePreview?.classList.add('hidden');
                setStatus('');
                return;
            }

            if (!file.type.startsWith('image/')) {
                imageInput.value = '';
                setStatus('Please choose a JPG, PNG, or WebP image.', true);
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                if (!imagePreview) return;

                imagePreview.src = event.target.result;
                imagePreview.classList.remove('hidden');
                showCropPanel(true);
                setStatus('Adjust the crop, then click “Crop & use image”.');

                resetCropper();
                cropper = new Cropper(imagePreview, {
                    aspectRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });
            };
            reader.readAsDataURL(file);
        });

        const applyCrop = async () => {
            if (!cropper || !imageInput) return;

            processBtn.disabled = true;
            setStatus('Processing image…');

            try {
                const canvas = cropper.getCroppedCanvas({
                    maxWidth: maxWidthOrHeight,
                    maxHeight: maxWidthOrHeight,
                });

                const croppedFile = await new Promise((resolve, reject) => {
                    canvas.toBlob(
                        (blob) => {
                            if (!blob) {
                                reject(new Error('Could not crop image.'));
                                return;
                            }
                            resolve(new File([blob], 'story-thumbnail.jpg', { type: 'image/jpeg' }));
                        },
                        'image/jpeg',
                        0.9
                    );
                });

                const compressedBlob = await imageCompression(croppedFile, {
                    maxSizeMB: maxSizeMb,
                    maxWidthOrHeight,
                    useWebWorker: true,
                    fileType: 'image/jpeg',
                });

                const compressedFile = new File([compressedBlob], 'story-thumbnail.jpg', {
                    type: 'image/jpeg',
                });

                const transfer = new DataTransfer();
                transfer.items.add(compressedFile);
                imageInput.files = transfer.files;
                readyFile = compressedFile;

                const compressedUrl = URL.createObjectURL(compressedBlob);
                if (finalOutput) {
                    finalOutput.src = compressedUrl;
                }
                resultWrap?.classList.remove('hidden');
                showCropPanel(false);
                imagePreview.classList.add('hidden');
                resetCropper();

                const sizeKb = (compressedBlob.size / 1024).toFixed(1);
                setStatus(`Image ready (${sizeKb} KB). You can submit the story.`);
            } catch (error) {
                console.error(error);
                setStatus('Could not process the image. Try another file.', true);
            } finally {
                processBtn.disabled = false;
            }
        };

        processBtn?.addEventListener('click', applyCrop);

        form?.addEventListener('submit', (e) => {
            const file = imageInput?.files?.[0];
            if (!file) return;

            if (cropper && !readyFile) {
                e.preventDefault();
                setStatus('Click “Crop & use image” before submitting.', true);
                processBtn?.focus();
            }
        });
    });
})();
