@extends('layouts.admin')

@section('title', 'Meetups')
@section('heading', 'Meetups')
@section('lead', 'Arranged handovers, who is meeting whom, and whether they were completed.')

@section('content')
<form class="fi-card fi-card-pad mb-4" method="GET" action="{{ route('admin.meetups.index') }}">
  <div class="row g-3 align-items-end">
    <div class="col-8 col-md-4"><label class="form-label fw-semibold" for="m-status">Status</label>
      <select id="m-status" name="status" class="form-select"><option value="">All</option>
        @foreach(['proposed', 'changed', 'confirmed', 'completed', 'cancelled'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>@endforeach
      </select></div>
    <div class="col-4 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Filter</button></div>
  </div>
</form>

<div class="fi-card overflow-hidden">
  <div class="table-responsive">
    <table class="table admin-table table-hover align-middle">
      <thead><tr><th>Item</th><th>Poster</th><th>Claimant</th><th>Where</th><th>When</th><th>Status</th></tr></thead>
      <tbody>
        @forelse($meetups as $m)
          @php $item = $m->claim->item; @endphp
          <tr>
            <td class="cell-wrap"><a href="{{ route('items.show', $item) }}" class="fw-semibold text-decoration-none">{{ $item->title }}</a></td>
            <td>@if($item->owner)<a href="{{ route('admin.users.show', $item->owner) }}" class="text-decoration-none">{{ $item->owner->name }}</a>@endif</td>
            <td>@if($m->claim->claimant)<a href="{{ route('admin.users.show', $m->claim->claimant) }}" class="text-decoration-none">{{ $m->claim->claimant->name }}</a>@endif</td>
            <td class="cell-wrap small">{{ $m->location }}</td>
            <td class="small text-nowrap">{{ $m->datetime->format('j M Y, g:i A') }}</td>
            <td><x-status-badge :status="$m->status" /></td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-secondary py-4">No meetups yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $meetups->links() }}</div>
@endsection
