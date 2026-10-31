@props(['claim'])
@php $restricted = auth()->user()->restricted; @endphp
<div class="border rounded-3 p-3">
  <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
    <strong>{{ $claim->claimant->name ?? 'Unknown user' }}</strong>
    <x-status-badge :status="$claim->status" />
  </div>
  <dl class="small mb-2">
    <dt>Answer to your question</dt><dd class="text-break">{{ $claim->answer }}</dd>
    <dt>Supporting details</dt><dd class="text-break mb-0">{{ $claim->details }}</dd>
  </dl>

  @if($claim->status === 'declined' && $claim->decline_reason)
    <p class="small text-secondary mb-0">Reason given: {{ $claim->decline_reason }}</p>
  @endif

  @if($claim->status === 'pending')
    <div class="d-flex flex-wrap gap-2">
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#approve-{{ $claim->id }}" @disabled($restricted)>Approve &amp; propose meetup</button>
      <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#decline-{{ $claim->id }}" @disabled($restricted)>Decline</button>
      <a class="btn btn-link btn-sm" href="{{ route('dashboard.messages', ['item' => $claim->item_id, 'with' => $claim->claimant_id]) }}">Message</a>
    </div>

    <div id="review-{{ $claim->id }}">
      <form id="approve-{{ $claim->id }}" class="collapse mt-3" method="POST" action="{{ route('claims.approve', $claim) }}" data-bs-parent="#review-{{ $claim->id }}">
        @csrf
        <div class="d-grid gap-2">
          <label class="form-label small mb-0" for="loc-{{ $claim->id }}">Public meeting place</label>
          <input id="loc-{{ $claim->id }}" name="location" class="form-control" placeholder="e.g. Marina Mall main entrance" required>
          <label class="form-label small mb-0" for="dt-{{ $claim->id }}">Date and time</label>
          <input id="dt-{{ $claim->id }}" name="datetime" type="datetime-local" class="form-control" required>
          <label class="form-label small mb-0" for="nt-{{ $claim->id }}">Note (optional)</label>
          <input id="nt-{{ $claim->id }}" name="note" class="form-control">
          <div><button class="btn btn-primary btn-sm" type="submit">Confirm approval</button></div>
        </div>
      </form>

      <form id="decline-{{ $claim->id }}" class="collapse mt-3" method="POST" action="{{ route('claims.decline', $claim) }}" data-bs-parent="#review-{{ $claim->id }}">
        @csrf
        <div class="d-grid gap-2">
          <label class="form-label small mb-0" for="rs-{{ $claim->id }}">Reason shown to the claimant</label>
          <textarea id="rs-{{ $claim->id }}" name="reason" class="form-control" required></textarea>
          <div><button class="btn btn-primary btn-sm" type="submit">Confirm decline</button></div>
        </div>
      </form>
    </div>
  @endif
</div>
