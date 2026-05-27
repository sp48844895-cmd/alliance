@extends('layouts.admin')

@section('title', 'Create membership')
@section('page_title', 'Create membership')
@section('breadcrumb')
    <a href="{{ route('admin.memberships.index') }}">Memberships</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Create</span>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.memberships.store') }}"
    enctype="multipart/form-data" class="space-y-5">
    @csrf

    @include('admin.memberships._form', ['districts' => $districts, 'blocks' => $blocks])

    <div class="flex items-center justify-end gap-2">
        <a href="{{ route('admin.memberships.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Create membership
        </button>
    </div>
</form>
@endsection
