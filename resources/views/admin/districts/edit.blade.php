@extends('layouts.admin')

@section('title', 'Edit district')
@section('page_title', 'Edit district')
@section('breadcrumb')
    <a href="{{ route('admin.districts.index') }}">Districts</a>
    <i class="bi bi-chevron-right text-[10px]"></i>
    <span class="text-[var(--color-ink-2)]">{{ $district->district_name }}</span>
@endsection

@section('topbar_actions')
    <form method="POST" action="{{ route('admin.districts.destroy', $district->id) }}"
        data-confirm="Delete this district? Only allowed if no blocks reference it.">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Delete
        </button>
    </form>
@endsection

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.districts.update', $district->id) }}"
        class="card p-5 lg:p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="label" for="district_name">District name</label>
            <input type="text" id="district_name" name="district_name"
                value="{{ old('district_name', $district->district_name) }}"
                maxlength="50" required class="input">
        </div>

        <div>
            <label class="label" for="status">Status</label>
            <select id="status" name="status" required class="select">
                <option value="1" @selected((string) old('status', $district->status) === '1')>Active</option>
                <option value="0" @selected((string) old('status', $district->status) === '0')>Inactive</option>
            </select>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <a href="{{ route('admin.districts.index') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Update district
            </button>
        </div>
    </form>
</div>
@endsection
