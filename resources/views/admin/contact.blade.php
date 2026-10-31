@extends('layouts.admin')

@section('title', 'Contact inbox')
@section('heading', 'Contact inbox')
@section('lead', 'Messages sent through the public contact form.')

@section('content')
@forelse($messages as $m)
  <div class="fi-card fi-card-pad mb-3">
    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
      <div><div class="fw-bold">{{ $m->subject ?: 'No subject' }}</div>
        <div class="small text-secondary">{{ $m->name }} &middot; <a href="mailto:{{ $m->email }}">{{ $m->email }}</a></div></div>
      <div class="small text-secondary text-nowrap">{{ $m->created_at->format('j M Y, g:i A') }}</div>
    </div>
    <p class="mb-0 text-break" style="white-space:pre-line">{{ $m->message }}</p>
  </div>
@empty
  <div class="fi-card fi-card-pad text-center text-secondary">No messages yet.</div>
@endforelse
<div class="mt-4">{{ $messages->links() }}</div>
@endsection
