@extends('layouts.admin')

@section('title', 'Items')
@section('heading', 'Items')
@section('lead', 'Every report on the platform and its current status.')

@section('content')
<form class="fi-card fi-card-pad mb-4" method="GET" action="{{ route('admin.items.index') }}">
  <div class="row g-3 align-items-end">
    <div class="col-12 col-md-4"><label class="form-label fw-semibold" for="i-q">Search</label><input id="i-q" name="q" class="form-control" placeholder="Title, place or poster" value="{{ request('q') }}"></div>
    <div class="col-6 col-md-3"><label class="form-label fw-semibold" for="i-type">Type</label>
      <select id="i-type" name="type" class="form-select"><option value="">All</option><option value="lost" @selected(request('type') === 'lost')>Lost</option><option value="found" @selected(request('type') === 'found')>Found</option></select></div>
    <div class="col-6 col-md-3"><label class="form-label fw-semibold" for="i-status">Status</label>
      <select id="i-status" name="status" class="form-select"><option value="">All</option>
        @foreach(['open' => 'Open', 'pending' => 'Claim pending', 'meetup' => 'Meetup arranged', 'returned' => 'Returned'] as $v => $l)<option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>@endforeach
      </select></div>
    <div class="col-12 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Filter</button></div>
  </div>
</form>

<div class="fi-card overflow-hidden">
  <div class="table-responsive">
    <table class="table admin-table table-hover align-middle">
      <thead><tr><th>Item</th><th>Type</th><th>Category</th><th>Posted by</th><th class="text-center">Claims</th><th>Status</th><th>Posted</th></tr></thead>
      <tbody>
        @forelse($items as $i)
          <tr>
            <td class="cell-wrap"><a href="{{ route('items.show', $i) }}" class="fw-semibold text-decoration-none">{{ $i->title }}</a><div class="small text-secondary">{{ $i->location }}</div></td>
            <td><x-type-badge :type="$i->type" /></td>
            <td class="small">{{ $i->category }}</td>
            <td>@if($i->owner)<a href="{{ route('admin.users.show', $i->owner) }}" class="text-decoration-none">{{ $i->owner->name }}</a>@else<span class="text-secondary">Deleted user</span>@endif</td>
            <td class="text-center">{{ $i->claims_count }}</td>
            <td><x-status-badge :status="$i->status" /></td>
            <td class="small text-nowrap">{{ $i->created_at->format('j M Y') }}</td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-secondary py-4">No items match those filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
