@extends('layouts.dashboard')

@section('title', 'My items | FoundIt')

@section('panel')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
  <h2 class="h4 fw-bold mb-0">My items</h2>
  <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> New report</a>
</div>
@if($items->count())
  <div class="d-grid gap-3">
    @foreach($items as $i)
      <div class="fi-card row-card">
        <div class="d-flex gap-3 align-items-center flex-grow-1 min-w-0">
          <x-img :src="$i->image_url" alt="" class="row-thumb" />
          <div class="min-w-0">
            <div class="fw-bold text-break">{{ $i->title }}</div>
            <div class="small text-secondary">{{ $i->location }} &middot; {{ $i->claims_count }} {{ Str::plural('claim', $i->claims_count) }}</div>
            <div class="d-flex gap-2 mt-1 flex-wrap"><x-type-badge :type="$i->type" /><x-status-badge :status="$i->status" /></div>
          </div>
        </div>
        <a href="{{ route('items.show', $i) }}" class="btn btn-outline-primary btn-sm">Open</a>
      </div>
    @endforeach
  </div>
  <div class="mt-4">{{ $items->links() }}</div>
@else
  <div class="fi-card fi-card-pad text-center"><p class="text-secondary">You have not reported anything yet.</p><a href="{{ route('items.create') }}" class="btn btn-primary">Report an item</a></div>
@endif
@endsection
