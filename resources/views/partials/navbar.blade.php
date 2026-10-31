@php
  $me = auth()->user();
  $isAdmin = $me && $me->isAdmin();
  $links = [
    ['route' => 'index',        'label' => 'Home',          'match' => 'index'],
    ['route' => 'browse',       'label' => 'Browse items',  'match' => 'browse'],
    ['route' => 'items.create', 'label' => 'Report an item','match' => 'items.create', 'hideForAdmin' => true],
    ['route' => 'about',        'label' => 'About',         'match' => 'about'],
    ['route' => 'contact',      'label' => 'Contact',       'match' => 'contact'],
  ];
@endphp
<nav class="navbar navbar-expand-lg fi-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold me-auto me-lg-3" href="{{ route('index') }}">
      <span class="brand-mark"><i class="bi bi-search-heart"></i></span> FoundIt
    </a>

    <div class="d-flex align-items-center gap-2 order-2 order-lg-3">
      <button type="button" class="btn btn-icon" data-theme-toggle aria-label="Switch light or dark theme"><i class="bi bi-moon-stars"></i></button>

      <div class="account-wrap">
        <button type="button" class="btn btn-icon" data-account-toggle aria-haspopup="true" aria-expanded="false" aria-label="Account menu">
          <i class="bi {{ $me ? 'bi-person-check' : 'bi-person-circle' }} fs-5"></i>
        </button>
        <div class="account-menu" role="menu" hidden>
          @if($me)
            <div class="menu-head">
              <div class="fw-bold text-truncate">Hi, {{ Str::before($me->name, ' ') }}</div>
              <div class="small text-secondary text-truncate">{{ $me->email }}</div>
            </div>
            @if($isAdmin)
              <a href="{{ route('admin.index') }}" class="menu-item" role="menuitem"><i class="bi bi-shield-lock"></i> Admin panel</a>
            @else
              <a href="{{ route('dashboard.index') }}" class="menu-item" role="menuitem"><i class="bi bi-speedometer2"></i>My Dashboard</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">@csrf
              <button type="submit" class="menu-item" role="menuitem"><i class="bi bi-box-arrow-right"></i> Log out</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="menu-item" role="menuitem"><i class="bi bi-box-arrow-in-right"></i> Sign in</a>
            <a href="{{ route('register') }}" class="menu-item" role="menuitem"><i class="bi bi-person-plus"></i> Create account</a>
          @endif
        </div>
      </div>

      <button class="btn btn-icon d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-4"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse order-3 order-lg-2" id="main-nav">
      <ul class="navbar-nav ms-lg-auto me-lg-3 gap-lg-1 py-2 py-lg-0">
        @foreach($links as $l)
          @continue($isAdmin && !empty($l['hideForAdmin']))
          <li class="nav-item"><a href="{{ route($l['route']) }}" class="nav-link {{ request()->routeIs($l['match']) ? 'active' : '' }}">{{ $l['label'] }}</a></li>
        @endforeach
      </ul>
    </div>
  </div>
</nav>
