@extends('layouts.app')

@section('title', 'Report an item | FoundIt')

@section('content')
@php $me = auth()->user(); @endphp
<x-page-banner :image="config('foundit.photos.report')" icon="bi-plus-circle" title="Report an item" subtitle="The more specific you are, the faster it finds its way home." />

<div class="container section pt-4">
  <div class="row justify-content-center"><div class="col-12 col-lg-9 col-xl-8">
    @if($me->restricted)<x-notice kind="warn" class="mb-3">You cannot create new reports while your account is restricted. {{ $me->restriction_reason }}</x-notice>@endif
    @include('partials.errors')

    <form class="fi-card fi-card-pad d-grid gap-3" method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
      @csrf
      <fieldset class="d-grid gap-3 border-0 p-0 m-0 min-w-0" @disabled($me->restricted)>
        <div>
          <span class="form-label fw-semibold d-block">What happened?</span>
          <div class="btn-group w-100" role="group" aria-label="Report type">
            <input type="radio" class="btn-check" name="type" id="t-lost" value="lost" autocomplete="off" @checked(old('type', 'lost') === 'lost')>
            <label class="btn btn-outline-primary" for="t-lost">I lost something</label>
            <input type="radio" class="btn-check" name="type" id="t-found" value="found" autocomplete="off" @checked(old('type') === 'found')>
            <label class="btn btn-outline-primary" for="t-found">I found something</label>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-12 col-md-7"><label class="form-label fw-semibold" for="title">Title</label><input id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Black leather wallet" required></div>
          <div class="col-12 col-md-5"><label class="form-label fw-semibold" for="category">Category</label>
            <select id="category" name="category" class="form-select" required>
              <option value="">Choose...</option>
              @foreach(config('foundit.categories') as $c)<option @selected(old('category') === $c)>{{ $c }}</option>@endforeach
            </select></div>
        </div>

        <div><label class="form-label fw-semibold" for="description">Description</label>
          <textarea id="description" name="description" class="form-control" placeholder="Colour, brand, condition. Keep one detail private." required>{{ old('description') }}</textarea></div>

        <div class="row g-3">
          <div class="col-12 col-md-7"><label class="form-label fw-semibold" for="location">Where was it lost or found?</label><input id="location" name="location" class="form-control" value="{{ old('location') }}" required></div>
          <div class="col-12 col-md-5"><label class="form-label fw-semibold" for="date">Date</label><input id="date" name="date" type="date" max="{{ now()->toDateString() }}" class="form-control" value="{{ old('date', now()->toDateString()) }}" required></div>
        </div>

        <div><label class="form-label fw-semibold" for="question">Verification question</label>
          <input id="question" name="question" class="form-control" value="{{ old('question') }}" placeholder="Something only the real owner would know" required>
          <div class="form-text">Only you will see the answers people give.</div></div>

        <div>
          <label class="form-label fw-semibold" for="photo">Photo <span class="text-secondary fw-normal">(optional)</span></label>
          <div class="upload-box">
            <div class="text-center text-secondary small p-3" id="photo-empty"><i class="bi bi-image fs-2 d-block mb-1"></i>Preview appears here</div>
            <img id="photo-preview" class="upload-preview" alt="Preview of the uploaded item" hidden>
            <div class="flex-grow-1">
              <input id="photo" name="image" type="file" accept="image/*" class="form-control">
              <div class="form-text">JPG, PNG or WebP, up to 8 MB.</div>
            </div>
          </div>
        </div>

        <button class="btn btn-primary btn-lg" type="submit">Publish report</button>
      </fieldset>
    </form>
  </div></div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('photo').addEventListener('change', function (e) {
    var file = e.target.files[0], img = document.getElementById('photo-preview'), empty = document.getElementById('photo-empty');
    if (!file) { img.hidden = true; empty.hidden = false; return; }
    img.src = URL.createObjectURL(file); img.hidden = false; empty.hidden = true;
  });
</script>
@endpush
