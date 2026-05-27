@extends('layouts.admin')

@section('title', 'Edit membership')
@section('page_title', 'Edit membership')
@section('breadcrumb')
    <a href="{{ route('admin.memberships.index') }}">Memberships</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <a href="{{ route('admin.memberships.show', $member->id) }}">{{ \Illuminate\Support\Str::limit($member->name, 28) }}</a>
    <i class="bi bi-chevron-right text-xs"></i>
    <span class="text-[var(--color-ink-2)]">Edit</span>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.memberships.show', $member->id) }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-eye"></i> View
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.memberships.update', $member->id) }}"
    enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    @include('admin.memberships._form', [
        'districts' => $districts,
        'blocks' => $blocks,
        'member' => $member,
        'memberImageUrl' => $memberImageUrl ?? null,
    ])

    <div class="flex items-center justify-end gap-2">
        <a href="{{ route('admin.memberships.show', $member->id) }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Update membership
        </button>
    </div>
</form>
@endsection
