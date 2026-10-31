<footer class="fi-footer mt-5">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-12 col-lg-4">
        <div class="d-flex align-items-center gap-2 fw-bold fs-5 mb-2"><span class="brand-mark"><i class="bi bi-search-heart"></i></span> FoundIt</div>
        <p class="small footer-muted mb-0">Lost something? Found something? FoundIt helps people verify ownership, talk safely and meet in public to return what matters.</p>
      </div>
      <div class="col-6 col-lg-2">
        <h2 class="h6 fw-bold">Explore</h2>
        <ul class="list-unstyled small mb-0 d-grid gap-2">
          <li><a href="{{ route('index') }}">Home</a></li>
          <li><a href="{{ route('browse') }}">Browse items</a></li>
          @unless(auth()->check() && auth()->user()->isAdmin())<li><a href="{{ route('items.create') }}">Report an item</a></li>@endunless
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h2 class="h6 fw-bold">Company</h2>
        <ul class="list-unstyled small mb-0 d-grid gap-2">
          <li><a href="{{ route('about') }}">About</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
          @guest<li><a href="{{ route('login') }}">Sign in</a></li>@endguest
        </ul>
      </div>
      <div class="col-12 col-lg-4">
        <h2 class="h6 fw-bold">Visit us</h2>
        <p class="small footer-muted mb-0"><i class="bi bi-geo-alt me-2"></i>Al Maryah Island, Abu Dhabi, UAE<br><i class="bi bi-envelope me-2 mt-2 d-inline-block"></i>hello@foundit.com</p>
      </div>
    </div>
    <hr class="my-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 small footer-muted">
      <span>&copy; {{ date('Y') }} FoundIt. All rights reserved.</span>
      <span>Always meet in a public place.</span>
    </div>
  </div>
</footer>
