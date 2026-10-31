@extends('layouts.app')

@section('title', $item->title.' | FoundIt')

@section('content')
@php
  $me = auth()->user();
  $isAdmin = $me && $me->isAdmin();
  $isOwner = $me && $me->id === $item->owner_id;
  $isFound = $item->type === 'found';
  $status = $item->status;
  $ownerFirst = Str::before($item->owner->name ?? 'the poster', ' ');
@endphp
<x-page-banner :image="$item->image_url ?: config('foundit.photos.browse')" :title="$item->title" :subtitle="($isFound ? 'Found' : 'Lost').' at '.$item->location" />

<div class="container section pt-4">
  <nav aria-label="Breadcrumb" class="mb-3 small"><a href="{{ route('browse') }}"><i class="bi bi-arrow-left"></i> Back to all items</a></nav>
  <div class="row g-4">
    <div class="col-12 col-lg-7">
      <x-img :src="$item->image_url" :alt="$item->title" class="detail-image mb-4" loading="eager" />
      <div class="fi-card fi-card-pad">
        <div class="d-flex flex-wrap gap-2 mb-3"><x-type-badge :type="$item->type" /><x-status-badge :status="$status" /><span class="fi-badge status-open">{{ $item->category }}</span></div>
        <h2 class="h4 fw-bold">About this item</h2>
        <p class="text-break">{{ $item->description }}</p>
        <dl class="row small mb-0">
          <dt class="col-5 col-sm-3">Place</dt><dd class="col-7 col-sm-9">{{ $item->location }}</dd>
          <dt class="col-5 col-sm-3">Date</dt><dd class="col-7 col-sm-9">{{ $item->date->format('j F Y') }}</dd>
          <dt class="col-5 col-sm-3">Posted by</dt><dd class="col-7 col-sm-9">{{ $item->owner->name ?? 'Unknown' }}</dd>
        </dl>
      </div>
    </div>

    <div class="col-12 col-lg-5">
      <div class="fi-card fi-card-pad">
        @include('partials.errors')
        @if($isAdmin)
          {{-- Admins watch the platform; they never claim, report or message. --}}
          <h2 class="h5 fw-bold">Admin view</h2>
          <p class="text-secondary">Admin accounts cannot claim items or contact users. Use the admin panel to follow what happened on this listing.</p>
          <a class="btn btn-primary" href="{{ route('admin.claims.index', ['q' => $item->title]) }}"><i class="bi bi-hand-index-thumb"></i> See claims on this item</a>

        @elseif($isOwner)
          <h2 class="h5 fw-bold">Claims on your listing</h2>
          @if($me->restricted)<x-notice kind="warn" class="mb-3">Your account is restricted, so you can view claims but not decide on them.</x-notice>@endif
          @if($claims->isEmpty())
            <p class="text-secondary mb-0">No claims yet. We will show them here.</p>
          @else
            <div class="d-grid gap-3">@foreach($claims as $claim)<x-claim-review :claim="$claim" />@endforeach</div>
          @endif

        @elseif(!$me)
          <h2 class="h5 fw-bold">{{ $isFound ? 'Is this yours?' : 'Did you find this?' }}</h2>
          <p class="text-secondary">Sign in to submit a claim and message the poster.</p>
          <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('login') }}">Sign in</a>
            <a class="btn btn-outline-primary" href="{{ route('register') }}">Create account</a>
          </div>

        @elseif($myClaim)
          <h2 class="h5 fw-bold">Your claim</h2>
          <div class="mb-2"><x-status-badge :status="$myClaim->status" /></div>
          <p class="small text-secondary mb-2">Submitted {{ $myClaim->created_at->format('j M Y') }}. You can only submit one claim per item.</p>
          @if($myClaim->status === 'declined')<x-notice kind="error" class="mb-3">Declined{{ $myClaim->decline_reason ? ': '.$myClaim->decline_reason : '.' }}</x-notice>@endif
          @if($myMeetup)
            <x-notice kind="ok" class="mb-3">Meetup {{ $myMeetup->status }}: {{ $myMeetup->location }}, {{ $myMeetup->datetime->format('j M Y, g:i A') }}. <a href="{{ route('dashboard.meetups') }}">Manage meetup</a></x-notice>
          @endif
          @if($myClaim->status === 'returned')<x-notice kind="ok" class="mb-3">Item returned. Thank you for using FoundIt.</x-notice>@endif
          <a class="btn btn-outline-primary" href="{{ route('dashboard.messages', ['item' => $item->id, 'with' => $item->owner_id]) }}"><i class="bi bi-chat-dots"></i> Message {{ $ownerFirst }}</a>

        @elseif($status === 'returned')
          <x-notice kind="ok">This item has already been returned.</x-notice>

        @else
          <h2 class="h5 fw-bold">{{ $isFound ? 'Claim this item' : 'I found this item' }}</h2>
          @if($me->restricted)<x-notice kind="warn" class="mb-3">New claims are disabled while your account is restricted. {{ $me->restriction_reason }}</x-notice>@endif
          <form method="POST" action="{{ route('claims.store', $item) }}" class="d-grid gap-3">
            @csrf
            <div>
              <label class="form-label fw-semibold" for="answer">{{ $item->question }}</label>
              <input id="answer" name="answer" class="form-control" value="{{ old('answer') }}" @disabled($me->restricted) required>
            </div>
            <div>
              <label class="form-label fw-semibold" for="details">Supporting details</label>
              <textarea id="details" name="details" class="form-control" placeholder="Marks, contents, when and where it was lost..." @disabled($me->restricted) required>{{ old('details') }}</textarea>
            </div>
            <button class="btn btn-primary" type="submit" @disabled($me->restricted)>Submit claim</button>
          </form>
          <hr>
          <a class="btn btn-outline-primary w-100" href="{{ route('dashboard.messages', ['item' => $item->id, 'with' => $item->owner_id]) }}"><i class="bi bi-chat-dots"></i> Message {{ $ownerFirst }}</a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
