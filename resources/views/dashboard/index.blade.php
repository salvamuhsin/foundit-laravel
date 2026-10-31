@extends('layouts.dashboard')

@section('title', 'Dashboard | FoundIt')

@section('panel')
@php
  $cards = [
    ['bi-card-list', $stats['items'], 'My items', route('dashboard.items')],
    ['bi-hand-index-thumb', $stats['claims'], 'My claims', route('dashboard.claims')],
    ['bi-inbox', $stats['toReview'], 'Claims to review', route('dashboard.received')],
    ['bi-calendar-event', $stats['openMeetups'], 'Open meetups', route('dashboard.meetups')],
  ];
  $toReview = $stats['toReview'];
@endphp
<div class="d-grid gap-4">
  <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-4">
    @foreach($cards as [$icon, $n, $label, $url])
      <div class="col">
        <a href="{{ $url }}" class="fi-card fi-card-pad d-flex gap-3 align-items-center h-100 text-decoration-none text-reset hover-lift">
          <span class="icon-tile"><i class="bi {{ $icon }}"></i></span>
          <div><div class="stat-number">{{ $n }}</div><div class="small text-secondary">{{ $label }}</div></div>
        </a>
      </div>
    @endforeach
  </div>

  <div class="row g-4">
    <div class="col-12 col-lg-5">
      <div class="fi-card fi-card-pad h-100">
        <h2 class="h5 fw-bold mb-3">Needs your attention</h2>
        <ul class="list-unstyled d-grid gap-3 mb-0">
          <li class="d-flex gap-3 align-items-center"><span class="icon-tile"><i class="bi bi-inbox"></i></span>
            <div class="flex-grow-1">{{ $toReview ? $toReview.' '.Str::plural('claim', $toReview).' waiting for your review' : 'No claims waiting' }}</div>
            @if($toReview > 0)<a href="{{ route('dashboard.received') }}" class="btn btn-primary btn-sm">Review</a>@endif</li>
          <li class="d-flex gap-3 align-items-center"><span class="icon-tile"><i class="bi bi-calendar-check"></i></span>
            <div class="flex-grow-1">{{ $stats['needConfirm'] ? $stats['needConfirm'].' '.Str::plural('meetup', $stats['needConfirm']).' to confirm' : 'No meetups to confirm' }}</div>
            @if($stats['needConfirm'] > 0)<a href="{{ route('dashboard.meetups') }}" class="btn btn-primary btn-sm">Open</a>@endif</li>
        </ul>
      </div>
    </div>
    <div class="col-12 col-lg-7">
      <div class="fi-card fi-card-pad h-100">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 fw-bold mb-0">Your latest items</h2><a href="{{ route('dashboard.items') }}" class="small">View all</a></div>
        @if($recent->count())
          <div class="d-grid gap-3">
            @foreach($recent as $i)
              <a href="{{ route('items.show', $i) }}" class="d-flex gap-3 align-items-center text-decoration-none text-reset">
                <x-img :src="$i->image_url" alt="" class="row-thumb" />
                <div class="min-w-0 flex-grow-1"><div class="fw-semibold text-truncate">{{ $i->title }}</div>
                  <div class="d-flex gap-2 mt-1 flex-wrap"><x-type-badge :type="$i->type" /><x-status-badge :status="$i->status" /></div></div>
              </a>
            @endforeach
          </div>
        @else
          <div class="text-center py-3"><p class="text-secondary">You have not posted anything yet.</p><a href="{{ route('items.create') }}" class="btn btn-primary">Report an item</a></div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
