@extends('layouts.admin')

@section('title', 'Activity log')
@section('heading', 'Activity log')
@section('lead', 'A running record of what members do: sign-ins, reports, claims, decisions, meetups and messages.')

@section('content')
<form class="fi-card fi-card-pad mb-4" method="GET" action="{{ route('admin.activity.index') }}">
  <div class="row g-3 align-items-end">
    <div class="col-12 col-md-5"><label class="form-label fw-semibold" for="a-user">Member</label>
      <select id="a-user" name="user" class="form-select"><option value="">Everyone</option>
        @foreach($members as $u)<option value="{{ $u->id }}" @selected((string) request('user') === (string) $u->id)>{{ $u->name }}</option>@endforeach
      </select></div>
    <div class="col-8 col-md-5"><label class="form-label fw-semibold" for="a-action">Action</label>
      <select id="a-action" name="action" class="form-select"><option value="">All actions</option>
        @foreach($actions as $a)<option value="{{ $a }}" @selected(request('action') === $a)>{{ ucfirst(str_replace(['.', '_'], ' ', $a)) }}</option>@endforeach
      </select></div>
    <div class="col-4 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Filter</button></div>
  </div>
</form>

@include('admin._activity-table', ['logs' => $logs, 'showUser' => true])
<div class="mt-4">{{ $logs->links() }}</div>
@endsection
