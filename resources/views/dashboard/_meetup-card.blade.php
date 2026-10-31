@php
  $me = auth()->user();
  $claim = $m->claim;
  $item = $claim->item;
  $isOwner = $item->owner_id === $me->id;
  $other = $isOwner ? $claim->claimant : $item->owner;
  $awaiting = in_array($m->status, ['proposed', 'changed']);
  $closed = in_array($m->status, ['completed', 'cancelled']);
@endphp
<div class="fi-card fi-card-pad">
  <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
    <h3 class="h6 fw-bold mb-0 text-break">{{ $item->title }}</h3><x-status-badge :status="$m->status" />
  </div>
  <ul class="list-unstyled small mb-3 d-grid gap-1">
    <li><i class="bi bi-person me-2"></i>With {{ $other->name ?? 'Unknown' }}</li>
    <li><i class="bi bi-geo-alt me-2"></i>{{ $m->location }}</li>
    <li><i class="bi bi-clock me-2"></i>{{ $m->datetime->format('l, j F Y, g:i A') }}</li>
    @if($m->note)<li class="text-secondary"><i class="bi bi-sticky me-2"></i>{{ $m->note }}</li>@endif
    @if($awaiting)
      <li class="text-secondary">{{ $m->proposed_by == $me->id ? 'Waiting for the other person to confirm.' : ($m->proposer->name ?? 'Someone').' proposed this. Please confirm or suggest a change.' }}</li>
    @endif
  </ul>

  @unless($closed)
    <div class="d-flex flex-wrap gap-2">
      @if($awaiting && $m->proposed_by != $me->id)
        <form method="POST" action="{{ route('meetups.confirm', $m) }}">@csrf<button class="btn btn-primary btn-sm" type="submit" @disabled($me->restricted)>Confirm</button></form>
      @endif
      <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#change-{{ $m->id }}" @disabled($me->restricted)>Propose a change</button>
      @if($isOwner && $m->status === 'confirmed')
        <form method="POST" action="{{ route('claims.returned', $claim) }}">@csrf<button class="btn btn-accent btn-sm" type="submit" @disabled($me->restricted)>Mark item returned</button></form>
      @endif
      @if($other)<a class="btn btn-link btn-sm" href="{{ route('dashboard.messages', ['item' => $item->id, 'with' => $other->id]) }}">Message</a>@endif
    </div>

    <form id="change-{{ $m->id }}" class="collapse mt-3" method="POST" action="{{ route('meetups.change', $m) }}">
      @csrf
      <div class="d-grid gap-2">
        <label class="form-label small mb-0" for="l-{{ $m->id }}">Meeting place</label>
        <input id="l-{{ $m->id }}" name="location" class="form-control" value="{{ $m->location }}" required>
        <label class="form-label small mb-0" for="d-{{ $m->id }}">New date and time</label>
        <input id="d-{{ $m->id }}" name="datetime" type="datetime-local" class="form-control" required>
        <label class="form-label small mb-0" for="n-{{ $m->id }}">Note (optional)</label>
        <input id="n-{{ $m->id }}" name="note" class="form-control" value="{{ $m->note }}">
        <div><button class="btn btn-primary btn-sm" type="submit">Send proposal</button></div>
      </div>
    </form>
  @endunless
</div>
