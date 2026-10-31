@extends('layouts.admin')

@section('title', 'Overview')
@section('heading', 'Overview')
@section('lead', 'What people are doing on FoundIt right now.')

@section('content')
@php
  $cards = [
    ['bi-people', $counts['users'], 'Members', route('admin.users.index')],
    ['bi-shield-exclamation', $counts['restricted'], 'Restricted accounts', route('admin.users.index', ['status' => 'restricted'])],
    ['bi-card-list', $counts['items'], 'Items listed', route('admin.items.index')],
    ['bi-hand-index-thumb', $counts['claims'], 'Claims made', route('admin.claims.index')],
    ['bi-hourglass-split', $counts['pendingClaims'], 'Claims pending', route('admin.claims.index', ['status' => 'pending'])],
    ['bi-calendar-event', $counts['openMeetups'], 'Open meetups', route('admin.meetups.index')],
    ['bi-check2-circle', $counts['returned'], 'Items returned', route('admin.items.index', ['status' => 'returned'])],
    ['bi-envelope', $counts['contact'], 'Contact messages', route('admin.contact.index')],
  ];
@endphp
<div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-4 mb-4">
  @foreach($cards as [$icon, $n, $label, $url])
    <div class="col">
      <a href="{{ $url }}" class="fi-card fi-card-pad admin-count h-100 text-decoration-none text-reset hover-lift">
        <span class="icon-tile"><i class="bi {{ $icon }}"></i></span>
        <div><div class="stat-number">{{ number_format($n) }}</div><div class="small text-secondary">{{ $label }}</div></div>
      </a>
    </div>
  @endforeach
</div>

<div class="row g-4">
  <div class="col-12 col-xl-8">
    <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 fw-bold mb-0">Latest activity</h2><a href="{{ route('admin.activity.index') }}" class="small">Full activity log</a></div>
    @include('admin._activity-table', ['logs' => $latestActivity, 'showUser' => true])
  </div>
  <div class="col-12 col-xl-4">
    <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 fw-bold mb-0">Newest members</h2><a href="{{ route('admin.users.index') }}" class="small">All users</a></div>
    <div class="fi-card fi-card-pad">
      <ul class="list-unstyled d-grid gap-3 mb-0">
        @forelse($newestUsers as $u)
          <li class="d-flex align-items-center gap-3">
            <span class="avatar" style="width:2.6rem;height:2.6rem;font-size:.9rem">{{ collect(explode(' ', $u->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</span>
            <div class="min-w-0 flex-grow-1">
              <a href="{{ route('admin.users.show', $u) }}" class="fw-semibold text-decoration-none d-block text-truncate">{{ $u->name }}</a>
              <div class="small text-secondary">Joined {{ $u->created_at->diffForHumans() }}</div>
            </div>
            @if($u->restricted)<span class="fi-badge status-restricted">Restricted</span>@endif
          </li>
        @empty
          <li class="text-secondary">No members yet.</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection
