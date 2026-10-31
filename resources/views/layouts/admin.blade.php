<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('title', 'Admin') | FoundIt</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/foundit.css') }}" rel="stylesheet">
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
  <script>
    try { var t = JSON.parse(localStorage.getItem('foundit:theme')) || 'light'; document.documentElement.setAttribute('data-bs-theme', t); } catch (e) {}
  </script>
</head>
<body>
  {{-- Admin navbar --}}
  <nav class="navbar fi-navbar admin-navbar sticky-top">
    <div class="container-fluid px-3 px-lg-4">
      <button class="btn btn-icon d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Open admin menu"><i class="bi bi-list fs-4"></i></button>
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold me-auto" href="{{ route('admin.index') }}">
        <span class="brand-mark"><i class="bi bi-shield-lock"></i></span> FoundIt <span class="fi-badge status-open ms-1">Admin</span>
      </a>
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-primary btn-sm d-none d-sm-inline-flex" href="{{ route('index') }}"><i class="bi bi-box-arrow-up-right"></i> View site</a>
        <button type="button" class="btn btn-icon" data-theme-toggle aria-label="Switch light or dark theme"><i class="bi bi-moon-stars"></i></button>
        <div class="account-wrap">
          <button type="button" class="btn btn-icon" data-account-toggle aria-haspopup="true" aria-expanded="false" aria-label="Account menu"><i class="bi bi-person-check fs-5"></i></button>
          <div class="account-menu" role="menu" hidden>
            <div class="menu-head">
              <div class="fw-bold text-truncate">{{ auth()->user()->name }}</div>
              <div class="small text-secondary text-truncate">{{ auth()->user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button type="submit" class="menu-item" role="menuitem"><i class="bi bi-box-arrow-right"></i> Log out</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="admin-shell">
    @include('partials.admin-sidebar')
    <main class="admin-main p-3 p-lg-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
        <div>
          <h1 class="h3 fw-bold mb-1">@yield('heading')</h1>
          @hasSection('lead')<p class="text-secondary mb-0">@yield('lead')</p>@endif
        </div>
        @yield('actions')
      </div>
      @if(session('status'))<x-notice kind="ok" class="mb-3">{{ session('status') }}</x-notice>@endif
      @include('partials.errors')
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/foundit.js') }}"></script>
  @stack('scripts')
</body>
</html>
