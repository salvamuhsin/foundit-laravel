@extends('layouts.dashboard')

@section('title', 'My claims | FoundIt')

@section('panel')
<h2 class="h4 fw-bold mb-3">My claims</h2>
@if($claims->count())
  <div class="d-grid gap-3">
    @foreach($claims as $c)
      <div class="fi-card row-card">
        <div class="d-flex gap-3 align-items-center flex-grow-1 min-w-0">
          <x-img :src="$c->item->image_url" alt="" class="row-thumb" />
          <div class="min-w-0">
            <div class="fw-bold text-break">{{ $c->item->title }}</div>
            <div class="small text-secondary">Posted by {{ $c->item->owner->name ?? 'Unknown' }} &middot; claimed {{ $c->created_at->format('j M Y') }}</div>
            <div class="mt-1"><x-status-badge :status="$c->status" /></div>
            @if($c->status === 'declined' && $c->decline_reason)<div class="small text-secondary mt-1">Reason: {{ $c->decline_reason }}</div>@endif
          </div>
        </div>
        <a href="{{ route('items.show', $c->item) }}" class="btn btn-outline-primary btn-sm">View</a>
      </div>
    @endforeach
  </div>
  <div class="mt-4">{{ $claims->links() }}</div>
@else
  <div class="fi-card fi-card-pad text-center"><p class="text-secondary">You have not claimed any item yet.</p><a href="{{ route('browse') }}" class="btn btn-primary">Browse items</a></div>
@endif
@endsection
