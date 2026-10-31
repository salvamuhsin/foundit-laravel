@extends('layouts.dashboard')

@section('title', 'Claims to review | FoundIt')

@section('panel')
<h2 class="h4 fw-bold mb-1">Claims to review</h2>
<p class="text-secondary mb-3">People who answered the verification question on your items.</p>
@include('partials.errors')
@if($claims->count())
  <div class="row g-4">
    @foreach($claims as $c)
      <div class="col-12 col-lg-6">
        <div class="fi-card fi-card-pad h-100">
          <div class="mb-2"><a href="{{ route('items.show', $c->item) }}" class="fw-bold text-break">{{ $c->item->title }}</a></div>
          <x-claim-review :claim="$c" />
        </div>
      </div>
    @endforeach
  </div>
  <div class="mt-4">{{ $claims->links() }}</div>
@else
  <div class="fi-card fi-card-pad text-center"><p class="text-secondary mb-0">No one has claimed your items yet.</p></div>
@endif
@endsection
