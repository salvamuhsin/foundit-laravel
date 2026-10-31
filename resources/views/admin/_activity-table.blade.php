@php $showUser = $showUser ?? true; @endphp
<div class="fi-card overflow-hidden">
  <div class="table-responsive">
    <table class="table admin-table table-hover align-middle">
      <thead><tr><th>When</th>@if($showUser)<th>User</th>@endif<th>Action</th><th>Details</th></tr></thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td class="small text-nowrap" title="{{ $log->created_at->format('j M Y, g:i:s A') }}">{{ $log->created_at->diffForHumans() }}</td>
            @if($showUser)
              <td>@if($log->user)<a href="{{ route('admin.users.show', $log->user) }}" class="fw-semibold text-decoration-none">{{ $log->user->name }}</a>@else<span class="text-secondary">Deleted user</span>@endif</td>
            @endif
            <td><span class="fi-badge status-open">{{ ucfirst(str_replace(['.', '_'], ' ', $log->action)) }}</span></td>
            <td class="cell-wrap small text-secondary">{{ $log->description }}</td>
          </tr>
        @empty
          <tr><td colspan="{{ $showUser ? 4 : 3 }}" class="text-center text-secondary py-4">No activity recorded yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
