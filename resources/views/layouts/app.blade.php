<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="FoundIt - report lost items, post found items, verify ownership and arrange safe returns.">
  <title>@yield('title', 'FoundIt | Lost & Found, Returned Safely')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/foundit.css') }}" rel="stylesheet">
  <script>
    try { var t = JSON.parse(localStorage.getItem('foundit:theme')) || 'light'; document.documentElement.setAttribute('data-bs-theme', t); } catch (e) {}
  </script>
  @stack('head')
</head>
<body>
  <div class="d-flex flex-column min-vh-100">
    @include('partials.navbar')
    @include('partials.restricted-banner')
    @if(session('status'))
      <div class="container mt-3"><x-notice kind="ok">{{ session('status') }}</x-notice></div>
    @endif
    <main class="flex-grow-1">@yield('content')</main>
    @include('partials.footer')
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/foundit.js') }}"></script>
  @stack('scripts')
</body>
</html>
