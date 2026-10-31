@extends('layouts.app')

@section('title', 'Sign in | FoundIt')

@section('content')
@php
  $perks = [
    ['bi-megaphone', 'Report lost or found items in minutes'],
    ['bi-patch-check', 'Verify ownership with a private question'],
    ['bi-chat-dots', 'Message safely inside FoundIt'],
    ['bi-calendar-check', 'Arrange and track every return'],
  ];
@endphp
<x-page-banner :image="config('foundit.photos.auth')" icon="bi-box-arrow-in-right" title="Welcome back" subtitle="Sign in to manage your items, claims, messages and meetups." />

<div class="container section">
  <div class="row g-4 justify-content-center align-items-stretch">
    <div class="col-12 col-md-9 col-lg-5 p-4">
      <form class="fi-card fi-card-pad d-grid gap-3 h-100 align-content-start" method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <h2 class="h4 fw-bold mb-0">Sign in</h2>
        @if(session('needLogin'))<x-notice kind="warn">Please sign in to continue.</x-notice>@endif
        @include('partials.errors')
        <div><label class="form-label fw-semibold" for="email">Email</label><input id="email" name="email" type="email" autocomplete="email" class="form-control" value="{{ old('email') }}" required autofocus></div>
        <div><label class="form-label fw-semibold" for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" class="form-control" required></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="remember"><label class="form-check-label small" for="remember">Keep me signed in</label></div>
        <button class="btn btn-primary btn-lg" type="submit">Sign in</button>
        <div class="text-center small text-secondary">New to FoundIt? <a href="{{ route('register') }}">Create an account</a></div>
      </form>
    </div>
    <div class="col-12 col-md-9 col-lg-5 p-4">
      <div class="fi-card overflow-hidden h-100 d-flex flex-column ">
        <x-img :src="config('foundit.photos.community')" alt="People returning a lost item" class="w-100" style="height:180px;object-fit:cover" />
        <div class="fi-card-pad">
          <h2 class="h5 fw-bold mb-3">Why join FoundIt?</h2>
          <ul class="list-unstyled d-grid gap-3 mb-0">
            @foreach($perks as [$icon, $text])
              <li class="d-flex gap-3 align-items-center"><span class="icon-tile"><i class="bi {{ $icon }}"></i></span><span>{{ $text }}</span></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
