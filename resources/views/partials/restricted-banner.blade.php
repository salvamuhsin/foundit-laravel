@if(auth()->check() && auth()->user()->restricted)
  <div class="restricted-banner" role="alert">
    <div class="container d-flex gap-3 align-items-start py-3">
      <i class="bi bi-shield-exclamation fs-4 flex-shrink-0"></i>
      <div>
        <strong>Your account is restricted.</strong>
        <div class="small">{{ auth()->user()->restriction_reason }} You can still sign in and view everything you already have.</div>
      </div>
    </div>
  </div>
@endif
