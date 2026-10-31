@extends('layouts.app')

@section('title', 'Create account | FoundIt')

@section('content')
<x-page-banner :image="config('foundit.photos.community')" icon="bi-person-plus" title="Create your free account" subtitle="Join the community that returns lost things." />

<div class="container section pt-4 p-4">
  <div class="row justify-content-center"><div class="col-12 col-md-8 col-lg-5">
    <form class="fi-card fi-card-pad d-grid gap-3" method="POST" action="{{ route('register.store') }}">
      @csrf
      @include('partials.errors')
      <div><label class="form-label fw-semibold" for="name">Full name</label><input id="name" name="name" autocomplete="name" class="form-control" value="{{ old('name') }}" required></div>
      <div><label class="form-label fw-semibold" for="email">Email</label><input id="email" name="email" type="email" autocomplete="email" class="form-control" value="{{ old('email') }}" required></div>
      <div><label class="form-label fw-semibold" for="password">Password</label><input id="password" name="password" type="password" autocomplete="new-password" class="form-control" required><div class="form-text">Use at least 8 characters.</div></div>
      <button class="btn btn-primary btn-lg" type="submit">Create account</button>
      <div class="text-center small text-secondary">Already registered? <a href="{{ route('login') }}">Sign in</a></div>
    </form>
  </div></div>
</div>
@endsection
