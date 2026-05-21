@php
    $isEdit = isset($member);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <div class="lg:col-span-2 space-y-5">

        <div class="card p-5 space-y-4">
            <h3 class="font-display text-base text-[var(--color-ink-2)]">Personal details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="name">Name</label>
                    <input type="text" id="name" name="name" required maxlength="255"
                        value="{{ old('name', $isEdit ? $member->name : '') }}" class="input">
                </div>
                <div>
                    <label class="label" for="type">Type</label>
                    <select id="type" name="type" required class="select">
                        @foreach (['Individual', 'CSO/NGO', 'Volunteer', 'Firm/Organization'] as $opt)
                            <option value="{{ $opt }}"
                                @selected(old('type', $isEdit ? $member->type : '') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="mobile">Mobile</label>
                    <input type="text" id="mobile" name="mobile" required maxlength="255"
                        value="{{ old('mobile', $isEdit ? $member->mobile : '') }}" class="input">
                </div>
                <div>
                    <label class="label" for="email">Email</label>
                    <input type="email" id="email" name="email" required maxlength="255"
                        value="{{ old('email', $isEdit ? $member->email : '') }}" class="input">
                </div>
            </div>

            <div>
                <label class="label" for="address">Address</label>
                <textarea id="address" name="address" rows="3" class="textarea">{{ old('address', $isEdit ? $member->address : '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="district">District</label>
                    <select id="district" name="district" required class="select">
                        <option value="">— Select district —</option>
                        @foreach ($districts as $d)
                            <option value="{{ $d->id }}"
                                @selected((string) old('district', $isEdit ? $member->district : '') === (string) $d->id)>
                                {{ $d->district_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label" for="block">Block</label>
                    <select id="block" name="block" required class="select">
                        <option value="">— Select block —</option>
                        @foreach ($blocks as $b)
                            <option value="{{ $b->id }}"
                                @selected((string) old('block', $isEdit ? $member->block : '') === (string) $b->id)>
                                {{ $b->block_name }}{{ $b->district_name ? ' · ' . $b->district_name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="label" for="area">Areas of work</label>
                <input type="text" id="area" name="area"
                    value="{{ old('area', $isEdit ? $member->area : '') }}" class="input"
                    placeholder="Education, Nutrition, SBC">
                <p class="help">Comma-separated list.</p>
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h3 class="font-display text-base text-[var(--color-ink-2)]">Organisation</h3>
            <div>
                <label class="label" for="ngo_organization">NGO / Organisation name</label>
                <input type="text" id="ngo_organization" name="ngo_organization" maxlength="255"
                    value="{{ old('ngo_organization', $isEdit ? $member->ngo_organization : '') }}" class="input">
            </div>
            <div>
                <label class="label" for="org_intro">Introduction</label>
                <textarea id="org_intro" name="org_intro" rows="5" class="textarea">{{ old('org_intro', $isEdit ? $member->org_intro : '') }}</textarea>
            </div>
        </div>

        <div class="card p-5 space-y-4">
            <h3 class="font-display text-base text-[var(--color-ink-2)]">Social links</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label" for="fb"><i class="bi bi-facebook"></i> Facebook</label>
                    <input type="text" id="fb" name="fb" maxlength="255"
                        value="{{ old('fb', $isEdit ? $member->fb : '') }}" class="input">
                </div>
                <div>
                    <label class="label" for="insta"><i class="bi bi-instagram"></i> Instagram</label>
                    <input type="text" id="insta" name="insta" maxlength="255"
                        value="{{ old('insta', $isEdit ? $member->insta : '') }}" class="input">
                </div>
                <div>
                    <label class="label" for="twitter"><i class="bi bi-twitter-x"></i> Twitter / X</label>
                    <input type="text" id="twitter" name="twitter" maxlength="255"
                        value="{{ old('twitter', $isEdit ? $member->twitter : '') }}" class="input">
                </div>
                <div>
                    <label class="label" for="youtube"><i class="bi bi-youtube"></i> YouTube</label>
                    <input type="text" id="youtube" name="youtube" maxlength="255"
                        value="{{ old('youtube', $isEdit ? $member->youtube : '') }}" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label" for="website"><i class="bi bi-globe2"></i> Website</label>
                    <input type="text" id="website" name="website" maxlength="255"
                        value="{{ old('website', $isEdit ? $member->website : '') }}" class="input">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="card p-5">
            <label class="label">Profile image</label>
            <div class="space-y-3">
                <img id="img_preview"
                    src="{{ $isEdit ? ($memberImageUrl ?? '') : '' }}"
                    class="w-full max-h-56 object-contain rounded-md border border-[var(--color-line)] bg-[var(--color-paper)] {{ ($isEdit && !empty($memberImageUrl)) ? '' : 'hidden' }}"
                    alt="">
                <input type="file" name="img" accept="image/*"
                    data-image-preview="#img_preview" class="input">
                <p class="help">JPG/PNG/WebP up to 4 MB. Optional.</p>
            </div>
        </div>

        <div class="card p-5 space-y-2">
            <div class="label">Membership code</div>
            @if ($isEdit)
                <div class="font-mono text-sm text-[var(--color-clay-700)] bg-[var(--color-clay-50)] px-2 py-1 rounded inline-block">
                    {{ $member->code }}
                </div>
            @else
                <div class="text-sm text-[var(--color-ink-2)]">Auto-generated at save time.</div>
            @endif
            <div class="label mt-4">{{ $isEdit ? 'Joined' : 'Join date' }}</div>
            @if ($isEdit)
                <div class="text-sm text-[var(--color-ink-2)]">
                    {{ $member->date ? date('d M Y, h:i A', strtotime($member->date)) : '—' }}
                </div>
                <p class="help">Code and join date cannot be edited.</p>
            @else
                <div class="text-sm text-[var(--color-ink-2)]">Current date and time will be set automatically.</div>
            @endif
        </div>
    </div>
</div>
