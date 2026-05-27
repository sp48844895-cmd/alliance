@extends('layouts.admin')

@section('title', 'Edit block')
@section('page_title', 'Edit block')
@section('breadcrumb')
    <a href="{{ route('admin.blocks.index') }}">Blocks</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">{{ $block->block_name }}</span>
@endsection

@section('topbar_actions')
    <form method="POST" action="{{ route('admin.blocks.destroy', $block->id) }}"
        data-confirm="Delete this block? This cannot be undone.">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Delete
        </button>
    </form>
@endsection

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.blocks.update', $block->id) }}"
        class="card p-5 lg:p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="label" for="district_id">District</label>
            <select id="district_id" name="district_id" required class="select">
                <option value="">— Select district —</option>
                @foreach ($districts as $d)
                    <option value="{{ $d->id }}"
                        @selected((string) old('district_id', $block->district_id) === (string) $d->id)>
                        {{ $d->district_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="label" for="block_name">Block name</label>
            <input type="text" id="block_name" name="block_name"
                value="{{ old('block_name', $block->block_name) }}"
                maxlength="50" required class="input">
        </div>

        <div>
            <label class="label" for="status">Status</label>
            <select id="status" name="status" required class="select">
                <option value="1" @selected((string) old('status', $block->status) === '1')>Active</option>
                <option value="0" @selected((string) old('status', $block->status) === '0')>Inactive</option>
            </select>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <a href="{{ route('admin.blocks.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Update block
            </button>
        </div>
    </form>
</div>
@endsection
