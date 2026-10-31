@php
  $main = [
    ['admin.index',          'Overview',      'bi-speedometer2',     'admin.index'],
    ['admin.users.index',    'Users',         'bi-people',           'admin.users.*'],
    ['admin.claims.index',   'Claims',        'bi-hand-index-thumb', 'admin.claims.*'],
    ['admin.items.index',    'Items',         'bi-card-list',        'admin.items.*'],
    ['admin.meetups.index',  'Meetups',       'bi-calendar-event',   'admin.meetups.*'],
    ['admin.messages.index', 'Messages',      'bi-chat-dots',        'admin.messages.*'],
    ['admin.activity.index', 'Activity log',  'bi-activity',         'admin.activity.*'],
    ['admin.contact.index',  'Contact inbox', 'bi-envelope',         'admin.contact.*'],
  ];
@endphp
<div class="admin-side-col">
  <aside class="offcanvas-lg offcanvas-start admin-sidebar" tabindex="-1" id="adminSidebar" aria-label="Admin navigation">
    <div class="offcanvas-header">
      <h2 class="offcanvas-title h6 fw-bold mb-0">FoundIt admin</h2>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close menu"></button>
    </div>
    <div class="offcanvas-body flex-column p-3">
      <nav class="d-grid gap-1" aria-label="Admin sections">
        <div class="admin-nav-label">Manage</div>
        @foreach($main as [$route, $label, $icon, $match])
          <a href="{{ route($route) }}" class="admin-link {{ request()->routeIs($match) ? 'active' : '' }}"><i class="bi {{ $icon }}"></i>{{ $label }}</a>
        @endforeach
      </nav>
    </div>
  </aside>
</div>
