@extends('layouts.dashboard')

@section('title', 'Messages | FoundIt')

@section('panel')
@php
  $me = auth()->user();
  $isActive = fn ($t) => $active && $active->item->id === $t->item->id && $active->other->id === $t->other->id;
@endphp
<h2 class="h4 fw-bold mb-3">Messages</h2>
@if($me->restricted)<x-notice kind="warn" class="mb-3">You can read your conversations, but sending new messages is disabled while your account is restricted.</x-notice>@endif
@include('partials.errors')

<div class="chat-layout">
  {{-- On phones show either the list or the open chat --}}
  <aside class="fi-card thread-list {{ $active ? 'd-none d-lg-block' : '' }}" aria-label="Conversations">
    @forelse($threads as $t)
      <a href="{{ route('dashboard.messages', ['item' => $t->item->id, 'with' => $t->other->id]) }}" class="thread-btn text-decoration-none {{ $isActive($t) ? 'active' : '' }}">
        <div class="fw-bold text-truncate">{{ $t->other->name }}</div>
        <div class="small text-secondary text-truncate">{{ $t->item->title }}</div>
        <div class="small text-truncate">{{ $t->last->body ?? 'Start the conversation' }}</div>
      </a>
    @empty
      <p class="p-3 text-secondary mb-0">No conversations yet. Open an item and choose “Message”.</p>
    @endforelse
  </aside>

  <section class="fi-card d-flex flex-column {{ $active ? '' : 'd-none d-lg-flex' }}" aria-label="Conversation">
    @if($active)
      <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <a class="btn btn-icon d-lg-none" href="{{ route('dashboard.messages') }}" aria-label="Back to conversations"><i class="bi bi-arrow-left"></i></a>
        <div class="min-w-0">
          <div class="fw-bold text-truncate">{{ $active->other->name }}</div>
          <a class="small" href="{{ route('items.show', $active->item) }}">{{ $active->item->title }}</a>
        </div>
      </div>
      <div class="chat-scroll" id="chat-scroll">
        @if($active->messages->isEmpty())<p class="text-secondary text-center my-auto">Say hello and ask about the item.</p>@endif
        @foreach($active->messages as $m)
          <div class="bubble {{ $m->from_id == $me->id ? 'mine' : 'theirs' }}">{{ $m->body }}<time datetime="{{ $m->created_at->toIso8601String() }}">{{ $m->created_at->format('j M Y, g:i A') }}</time></div>
        @endforeach
      </div>
      <form class="p-3 border-top" method="POST" action="{{ route('dashboard.messages.send') }}">
        @csrf
        <input type="hidden" name="item_id" value="{{ $active->item->id }}">
        <input type="hidden" name="to_id" value="{{ $active->other->id }}">
        <div class="d-flex gap-2">
          <label class="visually-hidden" for="msg">Message</label>
          <input id="msg" name="body" class="form-control" maxlength="1000" autocomplete="off" required
                 placeholder="{{ $me->restricted ? 'Messaging is disabled for restricted accounts' : 'Write a message...' }}" @disabled($me->restricted)>
          <button class="btn btn-primary" type="submit" aria-label="Send message" @disabled($me->restricted)><i class="bi bi-send"></i></button>
        </div>
      </form>
    @else
      <p class="text-secondary text-center p-5 my-auto">Choose a conversation.</p>
    @endif
  </section>
</div>
@endsection

@push('scripts')
<script>var c = document.getElementById('chat-scroll'); if (c) c.scrollTop = c.scrollHeight;</script>
@endpush
