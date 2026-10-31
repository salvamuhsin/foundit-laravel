@extends('layouts.admin')

@section('title', 'Claims')
@section('heading', 'Claims')
@section('lead', 'Every claim members have made, who decided it and how it ended. Answers stay private to the poster.')

@section('content')
<form class="fi-card fi-card-pad mb-4" method="GET" action="{{ route('admin.claims.index') }}">
  <div class="row g-3 align-items-end">
    <div class="col-12 col-md-6"><label class="form-label fw-semibold" for="c-q">Search</label><input id="c-q" name="q" class="form-control" placeholder="Item title or member name" value="{{ request('q') }}"></div>
    <div class="col-8 col-md-4"><label class="form-label fw-semibold" for="c-status">Status</label>
      <select id="c-status" name="status" class="form-select">
        <option value="">All</option>
        @foreach(['pending', 'approved', 'declined', 'returned'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>@endforeach
      </select></div>
    <div class="col-4 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Filter</button></div>
  </div>
</form>

<div class="fi-card overflow-hidden">
  <div class="table-responsive">
    <table class="table admin-table table-hover align-middle">
      <thead><tr><th>Item</th><th>Claimed by</th><th>Posted by</th><th>Status</th><th>Submitted</th></tr></thead>
      <tbody>
        @forelse($claims as $c)
          <tr>
            <td class="cell-wrap"><a href="{{ route('items.show', $c->item) }}" class="fw-semibold text-decoration-none">{{ $c->item->title }}</a></td>
            <td>@if($c->claimant)<a href="{{ route('admin.users.show', $c->claimant) }}" class="text-decoration-none">{{ $c->claimant->name }}</a>@else<span class="text-secondary">Deleted user</span>@endif</td>
            <td>@if($c->item->owner)<a href="{{ route('admin.users.show', $c->item->owner) }}" class="text-decoration-none">{{ $c->item->owner->name }}</a>@else<span class="text-secondary">Deleted user</span>@endif</td>
            <td><x-status-badge :status="$c->status" />@if($c->decline_reason)<div class="small text-secondary mt-1 cell-wrap">{{ $c->decline_reason }}</div>@endif</td>
            <td class="small text-nowrap">{{ $c->created_at->format('j M Y, g:i A') }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-secondary py-4">No claims match those filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $claims->links() }}</div>
@endsection
