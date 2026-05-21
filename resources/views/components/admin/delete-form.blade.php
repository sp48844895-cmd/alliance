@props([
    'action',
    'message',
    'title' => 'Delete',
    'buttonClass' => 'btn btn-danger btn-sm',
    'iconClass' => '',
    'disabled' => false,
])

<form method="POST" action="{{ $action }}" data-confirm="{{ $message }}" {{ $attributes->merge(['class' => 'inline']) }}>
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $buttonClass }}" title="{{ $title }}" @disabled($disabled)>
        <i class="bi bi-trash {{ $iconClass }}"></i>
    </button>
</form>
