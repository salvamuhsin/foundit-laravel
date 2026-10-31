@extends('layouts.dashboard')

@section('title', 'Meetups | FoundIt')

@section('panel')
@php $me = auth()->user(); @endphp
<h2 class="h4 fw-bold mb-3">Meetups &amp; returns</h2>
@if($me->restricted)<x-notice kind="warn" class="mb-3">Meetup actions are disabled while your account is restricted. Existing meetups remain visible.</x-notice>@endif
@include('partials.errors')
<x-notice kind="ok" class="mb-4">Safety tip: meet in a busy public place and bring the claim confirmation.</x-notice>

<h2 class="h5 fw-bold mb-3">Upcoming</h2>
<div class="row g-3 mb-5">
  @forelse($active as $m)
    <div class="col-12 col-lg-6">@include('dashboard._meetup-card', ['m' => $m])</div>
  @empty
    <div class="col-12"><div class="fi-card fi-card-pad text-secondary">No active meetups. They appear here once a claim is approved.</div></div>
  @endforelse
</div>

<h2 class="h5 fw-bold mb-3">Completed</h2>
<div class="row g-3">
  @forelse($past as $m)
    <div class="col-12 col-lg-6">@include('dashboard._meetup-card', ['m' => $m])</div>
  @empty
    <div class="col-12"><div class="fi-card fi-card-pad text-secondary">Nothing completed yet.</div></div>
  @endforelse
</div>
@endsection
