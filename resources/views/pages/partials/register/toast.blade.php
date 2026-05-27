@if (session('register_toast'))
  <div class="site-toast site-toast--{{ session('register_toast.type') }}" id="site-toast" role="status" aria-live="polite">
    <div class="site-toast__icon" aria-hidden="true">
      @if (session('register_toast.type') === 'success')
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
      @else
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
      @endif
    </div>
    <div class="site-toast__body">
      <p class="site-toast__title">{{ session('register_toast.title') }}</p>
      <p class="site-toast__message">{{ session('register_toast.message') }}</p>
    </div>
    <button type="button" class="site-toast__close" data-toast-close aria-label="Close notification">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
@endif

@if ($errors->any())
  <div class="site-toast site-toast--error" id="site-toast" role="alert" aria-live="assertive">
    <div class="site-toast__icon" aria-hidden="true">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
    </div>
    <div class="site-toast__body">
      <p class="site-toast__title">Could not submit application</p>
      <p class="site-toast__message">Please check the form and try again.</p>
      <ul class="site-toast__errors">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    <button type="button" class="site-toast__close" data-toast-close aria-label="Close notification">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
@endif
