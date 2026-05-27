@php
    $isEdit = isset($event);
    $action = $isEdit
        ? route('admin.events.update', $event->id)
        : route('admin.events.store');

    $event_name  = old('event_name',  $isEdit ? $event->event_name : '');
    $start_date  = old('start_date',  $isEdit ? $event->start_date : '');
    $time        = old('time',        $isEdit ? $event->time : '');
    $end_date    = old('end_date',    $isEdit ? substr($event->end_date ?? '', 0, 5) : '');
    $location    = old('location',    $isEdit ? $event->location : 'NOT MENTIONED');
    $googlemap   = old('googlemap',   $isEdit ? $event->googlemap : '');
    $description = old('description', $isEdit ? $event->description : '');
    $statusVal   = old('event_status', $isEdit ? (string) $event->event_status : '1');
    $currentImage = $imageUrl ?? '';
    $hasImage = $currentImage !== '';
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 space-y-5">
            <div class="card p-5">
                <label class="label" for="event_name">Event name</label>
                <input type="text" id="event_name" name="event_name"
                    value="{{ $event_name }}" maxlength="100" required
                    class="input" placeholder="Annual SBC summit 2026">
                <p class="help">Max 100 characters.</p>
            </div>

            <div class="card p-5">
                <label class="label" for="description">Description</label>
                <textarea id="description" name="description" data-rich-editor rows="12"
                    required class="textarea min-h-[280px]" placeholder="Write a detailed event description...">{{ $description }}</textarea>
                <p class="help">Use headings, lists, and links. Formatting is saved with the event.</p>
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-5 space-y-4">
                <div>
                    <label class="label" for="start_date">Start date</label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ $start_date }}" required class="input">
                </div>

                <div>
                    <label class="label" for="time">Display time</label>
                    <input type="text" id="time" name="time"
                        value="{{ $time }}" maxlength="100" required
                        class="input" placeholder="11:00 AM">
                    <p class="help">Free text shown to visitors.</p>
                </div>

                <div>
                    <label class="label" for="end_date">End time</label>
                    <input type="time" id="end_date" name="end_date"
                        value="{{ $end_date }}" required class="input">
                </div>

                <div>
                    <label class="label" for="location">Location</label>
                    <input type="text" id="location" name="location"
                        value="{{ $location }}" maxlength="100" required
                        class="input" placeholder="Raipur, Chhattisgarh">
                </div>

                <div>
                    <label class="label" for="event_status">Status</label>
                    <select id="event_status" name="event_status" class="select" required>
                        <option value="1" @selected($statusVal === '1')>Active</option>
                        <option value="0" @selected($statusVal === '0')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="card p-5">
                <label class="label">Event image</label>
                <div class="space-y-3">
                    <div id="event_image_preview_box" class="rounded-md border border-[var(--color-line)] bg-[var(--color-paper-2)] min-h-[120px] flex items-center justify-center overflow-hidden {{ $hasImage ? '' : 'hidden' }}">
                        <img id="event_image_preview"
                            src="{{ $currentImage }}"
                            class="w-full max-h-56 object-contain"
                            alt="Event image preview">
                    </div>
                    <div id="event_image_placeholder" class="rounded-md border border-dashed border-[var(--color-line)] bg-[var(--color-paper-2)] min-h-[120px] flex flex-col items-center justify-center gap-2 text-[var(--color-mute)] {{ $hasImage ? 'hidden' : '' }}">
                        <i class="bi bi-image text-3xl"></i>
                        <span class="text-xs">No image uploaded</span>
                    </div>
                    <input type="file" id="event_image" name="event_image" accept="image/jpeg,image/png,image/webp,image/gif"
                        data-image-preview="#event_image_preview"
                        data-image-preview-box="#event_image_preview_box"
                        data-image-preview-placeholder="#event_image_placeholder"
                        class="input">
                    <p class="help">JPG, PNG, WebP or GIF.</p>
                    @if ($isEdit && $hasImage)
                        <label class="inline-flex items-center gap-2 text-sm text-[var(--color-mute)] cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-[var(--color-line)]">
                            Remove current image
                        </label>
                    @endif
                </div>
            </div>

            <div class="card p-5">
                <label class="label" for="googlemap">Google Maps / FB link</label>
                <textarea id="googlemap" name="googlemap" rows="3"
                    class="textarea" placeholder="https://maps.google.com/...">{{ $googlemap }}</textarea>
                <p class="help">Optional embed URL or share link.</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="{{ $isEdit ? route('admin.events.show', $event->id) : route('admin.events.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i>
            {{ $isEdit ? 'Update event' : 'Create event' }}
        </button>
    </div>
</form>
