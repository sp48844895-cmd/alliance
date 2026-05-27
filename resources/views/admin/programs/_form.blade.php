@php
    $isEdit     = isset($program);
    $action     = $isEdit ? route('admin.programs.update', $program->id) : route('admin.programs.store');
    $title      = old('title',      $isEdit ? $program->title : '');
    $tag        = old('tag',        $isEdit ? $program->tag : '');
    $short_desc = old('short_desc', $isEdit ? $program->short_desc : '');
    $full_desc  = old('full_desc',  $isEdit ? $program->full_desc : '');
    $card_style = old('card_style', $isEdit ? $program->card_style : 'default');
    $sort_order = old('sort_order', $isEdit ? $program->sort_order : 0);
    $statusVal  = old('status',     $isEdit ? (string) $program->status : '1');
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 space-y-5">
            <div class="card p-5">
                <label class="label" for="title">Program title</label>
                <input type="text" id="title" name="title"
                    value="{{ $title }}" maxlength="150" required
                    class="input" placeholder="e.g. Bapi Na Uwat">
                <p class="help">Max 150 characters.</p>
            </div>

            <div class="card p-5">
                <label class="label" for="short_desc">Short description</label>
                <textarea id="short_desc" name="short_desc" rows="5"
                    required class="textarea" placeholder="Primary description shown on the card back...">{{ $short_desc }}</textarea>
                <p class="help">Shown on the back of the flip card when hovered.</p>
            </div>

            <div class="card p-5">
                <label class="label" for="full_desc">Full description <span class="text-[var(--color-mute)] font-normal">(optional)</span></label>
                <textarea id="full_desc" name="full_desc" rows="6"
                    class="textarea" placeholder="Additional detail appended on the card back...">{{ $full_desc }}</textarea>
            </div>

            <div class="card p-5">
                <label class="label" for="image">Program photo</label>
                @if ($isEdit && !empty($program->image))
                    @php $preview = \App\Support\MediaUrl::tryResolve('program', (string) $program->image); @endphp
                    @if ($preview)
                        <img src="{{ $preview }}" alt="" class="w-full max-w-xs rounded-lg mb-3 object-cover aspect-[4/5]">
                    @endif
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="input">
                <p class="help">Front of the flip card. Recommended 560×720px (4:5).</p>
            </div>
        </div>

        <div class="space-y-5">
            <div class="card p-5 space-y-4">
                <div>
                    <label class="label" for="tag">Tag / label</label>
                    <input type="text" id="tag" name="tag"
                        value="{{ $tag }}" maxlength="150"
                        class="input" placeholder="e.g. Dantewada · Nutrition &amp; Health">
                    <p class="help">Short badge shown above the card title.</p>
                </div>

                <div>
                    <label class="label" for="card_style">Card style</label>
                    <select id="card_style" name="card_style" class="select" required>
                        @foreach(['featured' => 'Featured (large)', 'default' => 'Default (white)', 'teal' => 'Teal', 'ochre' => 'Ochre', 'leaf' => 'Leaf (green)'] as $val => $label)
                            <option value="{{ $val }}" @selected($card_style === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="help">Controls the card's visual appearance on the home page.</p>
                </div>

                <div>
                    <label class="label" for="sort_order">Sort order</label>
                    <input type="number" id="sort_order" name="sort_order"
                        value="{{ $sort_order }}" min="0" class="input">
                    <p class="help">Lower number appears first. Cards are sorted by this value.</p>
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
        <a href="{{ route('admin.programs.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i>
            {{ $isEdit ? 'Update program' : 'Create program' }}
        </button>
    </div>
</form>
