@extends('layouts.app')

@section('title', 'Page not found | FoundIt')

@section('content')
<x-page-banner :image="config('foundit.photos.auth')" icon="bi-compass" title="We couldn’t find that page" subtitle="The page you are looking for has moved, was removed or never existed." />
<div class="container section text-center">
  <p class="text-secondary">Let’s get you back on track.</p>
  <div class="d-flex gap-2 justify-content-center flex-wrap">
    <a href="{{ route('index') }}" class="btn btn-primary">Go home</a>
    <a href="{{ route('browse') }}" class="btn btn-outline-primary">Browse items</a>
  </div>
</div>
@endsection
