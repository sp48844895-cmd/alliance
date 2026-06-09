@php
    $isEdit = isset($slide);
    $action = $isEdit ? route('admin.home-slider.update', $slide->id) : route('admin.home-slider.store');
    $title = old('title', $isEdit ? $slide->title : '');
    $short_description = old('short_description', $isEdit ? $slide->short_description : '');
    $url = old('url', $isEdit ? $slide->url : '');
    $sort_order = old('sort_order', $isEdit ? $slide->sort_order : 0);
    $statusVal = old('status', $isEdit ? (string) $slide->status : '1');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            <div class="card p-5">
                <label class="label" for="title">Title</label>
                <input type="text" id="title" name="title"
                    value="{{ $title }}" maxlength="150" required
                    class="input" placeholder="e.g. Youth volunteer networks">
                <p class="help">Shown below the slider when this card is active.</p>
            </div>

            <div class="card p-5">
                <label class="label" for="short_description">Short description</label>
                <textarea id="short_description" name="short_description" rows="5"
                    required class="textarea" placeholder="Brief summary shown with the active card...">{{ $short_description }}</textarea>
            </div>

            <div class="card p-5">
                <label class="label" for="url">URL <span class="text-[var(--color-mute)] font-normal">(optional)</span></label>
                <input type="text" id="url" name="url"
                    value="{{ $url }}" maxlength="500"
                    class="input" placeholder="https://example.com/page or /programs-and-initiatives">
                <p class="help">Visitors are redirected when they click the active card image. Use a full URL, internal path (e.g. /contact), or email link (e.g. mailto:info@example.com).</p>
            </div>

            <div class="card p-5">
                <label class="label" for="image">Image @unless($isEdit)<span class="text-[var(--color-flame)]">*</span>@endunless</label>
                @if ($isEdit && ! empty($slide->image))
                    @php $preview = \App\Support\MediaUrl::tryResolve('home-slider', (string) $slide->image); @endphp
                    @if ($preview)
                        <img src="{{ $preview }}" alt="" class="w-full max-w-xs rounded-lg mb-3 object-cover aspect-[173/231]">
                    @endif
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="input"@unless($isEdit) required @endunless>
                <p class="help">Card image for the circular slider. Recommended portrait ratio (173×231).</p>
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-5 space-y-4">
                <div>
                    <label class="label" for="sort_order">Sort order</label>
                    <input type="number" id="sort_order" name="sort_order"
                        value="{{ $sort_order }}" min="0" class="input">
                    <p class="help">Lower number appears first in the slider.</p>
                </div>

                <div>
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="select" required>
                        <option value="1" @selected($statusVal === '1')>Active</option>
                        <option value="0" @selected($statusVal === '0')>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="{{ route('admin.home-slider.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i>
            {{ $isEdit ? 'Update slide' : 'Create slide' }}
        </button>
    </div>
</form>
