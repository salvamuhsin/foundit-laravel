@extends('layouts.admin')

@section('title', $user->name)
@section('heading', $user->name)
@section('lead', $user->email)

@section('actions')
  <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-left"></i> All users</a>
@endsection

@section('content')
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-5">
    <div class="fi-card fi-card-pad h-100">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="avatar">{{ collect(explode(' ', $user->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</span>
        <div>
          <div class="fw-bold">{{ $user->name }}</div>
          <div class="small text-secondary">Member since {{ $user->created_at->format('j F Y') }}</div>
          <div class="mt-1">@if($user->restricted)<span class="fi-badge status-restricted"><i class="bi bi-shield-exclamation"></i> Restricted</span>@else<span class="fi-badge status-active"><i class="bi bi-check-circle"></i> Active</span>@endif</div>
        </div>
      </div>
      <div class="row text-center g-2">
        <div class="col-4"><div class="stat-number">{{ $user->items_count }}</div><div class="small text-secondary">Items</div></div>
        <div class="col-4"><div class="stat-number">{{ $user->claims_count }}</div><div class="small text-secondary">Claims</div></div>
        <div class="col-4"><div class="stat-number">{{ $user->sent_messages_count }}</div><div class="small text-secondary">Messages sent</div></div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-7">
    <div class="fi-card fi-card-pad h-100">
      <h2 class="h5 fw-bold">Account restriction</h2>
      @if($user->isAdmin())
        <p class="text-secondary mb-0">This is an admin account and cannot be restricted.</p>
      @elseif($user->restricted)
        <x-notice kind="warn" class="mb-3">Restricted. Reason shown to the member: {{ $user->restriction_reason }}</x-notice>
        <p class="small text-secondary">While restricted, this member can sign in and read their records but cannot report items, claim, message or change meetups.</p>
        <form method="POST" action="{{ route('admin.users.unrestrict', $user) }}">@csrf
          <button class="btn btn-primary" type="submit"><i class="bi bi-unlock"></i> Lift restriction</button>
        </form>
      @else
        <p class="small text-secondary">Restricting stops new reports, claims, messages and meetup changes. The reason is shown to the member.</p>
        <form method="POST" action="{{ route('admin.users.restrict', $user) }}" class="d-grid gap-2">@csrf
          <label class="form-label fw-semibold mb-0" for="reason">Reason</label>
          <textarea id="reason" name="reason" class="form-control" placeholder="e.g. Repeated no-shows at arranged meetups." required>{{ old('reason') }}</textarea>
          <div><button class="btn btn-primary" type="submit"><i class="bi bi-lock"></i> Restrict account</button></div>
        </form>
      @endif
    </div>
  </div>
</div>

<h2 class="h5 fw-bold mb-2">Items posted</h2>
<div class="fi-card overflow-hidden mb-4">
  <div class="table-responsive"><table class="table admin-table table-hover">
    <thead><tr><th>Item</th><th>Type</th><th>Status</th><th>Posted</th></tr></thead>
    <tbody>
      @forelse($items as $i)
        <tr>
          <td class="cell-wrap"><a href="{{ route('items.show', $i) }}" class="fw-semibold text-decoration-none">{{ $i->title }}</a><div class="small text-secondary">{{ $i->location }}</div></td>
          <td><x-type-badge :type="$i->type" /></td>
          <td><x-status-badge :status="$i->status" /></td>
          <td class="small text-nowrap">{{ $i->created_at->format('j M Y') }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-secondary py-4">Nothing posted.</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>

<h2 class="h5 fw-bold mb-2">Claims made</h2>
<div class="fi-card overflow-hidden mb-4">
  <div class="table-responsive"><table class="table admin-table table-hover">
    <thead><tr><th>Item</th><th>Posted by</th><th>Status</th><th>Submitted</th></tr></thead>
    <tbody>
      @forelse($claims as $c)
        <tr>
          <td class="cell-wrap"><a href="{{ route('items.show', $c->item) }}" class="fw-semibold text-decoration-none">{{ $c->item->title }}</a></td>
          <td class="small">{{ $c->item->owner->name ?? 'Unknown' }}</td>
          <td><x-status-badge :status="$c->status" />@if($c->decline_reason)<div class="small text-secondary mt-1">{{ $c->decline_reason }}</div>@endif</td>
          <td class="small text-nowrap">{{ $c->created_at->format('j M Y') }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-secondary py-4">No claims made.</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>

<h2 class="h5 fw-bold mb-2">Meetups</h2>
<div class="fi-card overflow-hidden mb-4">
  <div class="table-responsive"><table class="table admin-table table-hover">
    <thead><tr><th>Item</th><th>Where</th><th>When</th><th>Status</th></tr></thead>
    <tbody>
      @forelse($meetups as $m)
        <tr>
          <td class="cell-wrap">{{ $m->claim->item->title }}</td>
          <td class="cell-wrap small">{{ $m->location }}</td>
          <td class="small text-nowrap">{{ $m->datetime->format('j M Y, g:i A') }}</td>
          <td><x-status-badge :status="$m->status" /></td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-secondary py-4">No meetups.</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>

<h2 class="h5 fw-bold mb-2">Activity</h2>
@include('admin._activity-table', ['logs' => $activity, 'showUser' => false])
<div class="mt-4">{{ $activity->links() }}</div>
@endsection
