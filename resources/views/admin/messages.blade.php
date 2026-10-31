@extends('layouts.admin')

@section('title', 'Messages')
@section('heading', 'Messages')
@section('lead', 'Who is messaging whom and about which item. Message text is private and is not shown here.')

@section('content')
<div class="fi-card overflow-hidden">
  <div class="table-responsive">
    <table class="table admin-table table-hover align-middle">
      <thead><tr><th>When</th><th>From</th><th>To</th><th>About item</th><th class="text-end">Length</th></tr></thead>
      <tbody>
        @forelse($messages as $m)
          <tr>
            <td class="small text-nowrap">{{ $m->created_at->format('j M Y, g:i A') }}</td>
            <td>@if($m->sender)<a href="{{ route('admin.users.show', $m->sender) }}" class="text-decoration-none">{{ $m->sender->name }}</a>@else<span class="text-secondary">Deleted user</span>@endif</td>
            <td>@if($m->recipient)<a href="{{ route('admin.users.show', $m->recipient) }}" class="text-decoration-none">{{ $m->recipient->name }}</a>@else<span class="text-secondary">Deleted user</span>@endif</td>
            <td class="cell-wrap">@if($m->item)<a href="{{ route('items.show', $m->item) }}" class="text-decoration-none">{{ $m->item->title }}</a>@else<span class="text-secondary">Removed item</span>@endif</td>
            <td class="text-end small text-secondary">{{ mb_strlen($m->body) }} characters</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-secondary py-4">No messages yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-4">{{ $messages->links() }}</div>
@endsection
