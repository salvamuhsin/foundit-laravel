@extends('layouts.app')

@section('content')
@php
  $me = auth()->user();
  $tabs = [
    ['dashboard.index',    'Overview',         'bi-grid-1x2'],
    ['dashboard.items',    'My items',         'bi-card-list'],
    ['dashboard.claims',   'My claims',        'bi-hand-index-thumb'],
    ['dashboard.received', 'Claims to review', 'bi-inbox'],
    ['dashboard.messages', 'Messages',         'bi-chat-dots'],
    ['dashboard.meetups',  'Meetups',          'bi-calendar-event'],
  ];
  $toReview = $me->pendingReviewCount();
@endphp
<x-page-banner :image="config('foundit.photos.dashboard')" icon="bi-speedometer2" :title="'Hi, '.Str::before($me->name, ' ')" subtitle="Your items, claims, messages and meetups in one place.">
  <a class="btn btn-accent mt-3" href="{{ route('items.create') }}"><i class="bi bi-plus-circle"></i> Report an item</a>
</x-page-banner>
<div class="container">
  <nav class="fi-card panel-nav" aria-label="Dashboard sections">
    @foreach($tabs as [$route, $label, $icon])
      <a href="{{ route($route) }}" class="panel-link {{ request()->routeIs($route) ? 'active' : '' }}">
        <i class="bi {{ $icon }}"></i>{{ $label }}
        @if($route === 'dashboard.received' && $toReview > 0)<span class="panel-count">{{ $toReview }}</span>@endif
      </a>
    @endforeach
  </nav>
  <div class="py-4 py-lg-5">@yield('panel')</div>
</div>
@endsection
