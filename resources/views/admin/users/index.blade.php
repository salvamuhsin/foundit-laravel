@extends('layouts.admin')

@section('title', 'Users')
@section('heading', 'Users')
@section('lead', 'Every member, what they have posted and claimed, and whether their account is restricted.')

@section('content')
<form class="fi-card fi-card-pad mb-4" method="GET" action="{{ route('admin.users.index') }}">
  <div class="row g-3 align-items-end">
    <div class="col-12 col-md-6"><label class="form-label fw-semibold" for="u-q">Search</label><input id="u-q" name="q" class="form-control" placeholder="Name or email" value="{{ request('q') }}"></div>
    <div class="col-8 col-md-4"><label class="form-label fw-semibold" for="u-status">Account</label>
      <select id="u-status" name="status" class="form-select">
        <option value="">All members</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="restricted" @selected(request('status') === 'restricted')>Restricted</option>
      </select></div>
    <div class="col-4 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Filter</button></div>
  </div>
</form>

<div class="fi-card overflow-hidden">
  <div class="table-responsive">
    <table class="table admin-table table-hover align-middle">
      <thead><tr><th>User</th><th>Joined</th><th class="text-center">Items</th><th class="text-center">Claims</th><th>Last activity</th><th>Account</th><th></th></tr></thead>
      <tbody>
        @forelse($users as $u)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <span class="avatar" style="width:2.4rem;height:2.4rem;font-size:.85rem">{{ collect(explode(' ', $u->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</span>
                <div class="min-w-0"><div class="fw-semibold">{{ $u->name }}</div><div class="small text-secondary">{{ $u->email }}</div></div>
              </div>
            </td>
            <td class="small text-nowrap">{{ $u->created_at->format('j M Y') }}</td>
            <td class="text-center">{{ $u->items_count }}</td>
            <td class="text-center">{{ $u->claims_count }}</td>
            <td class="small text-nowrap">{{ $u->activity_logs_max_created_at ? \Illuminate\Support\Carbon::parse($u->activity_logs_max_created_at)->diffForHumans() : 'No activity' }}</td>
            <td>@if($u->restricted)<span class="fi-badge status-restricted"><i class="bi bi-shield-exclamation"></i> Restricted</span>@else<span class="fi-badge status-active"><i class="bi bi-check-circle"></i> Active</span>@endif</td>
            <td class="text-end"><a href="{{ route('admin.users.show', $u) }}" class="btn btn-outline-primary btn-sm">View</a></td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-secondary py-4">No users match those filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
