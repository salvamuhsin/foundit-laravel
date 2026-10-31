@extends('layouts.app')

@section('title', 'Contact | FoundIt')

@section('content')
@php
  $info = [
    ['bi-geo-alt', 'Visit us', 'Al Maryah Island, Abu Dhabi, UAE'],
    ['bi-envelope', 'Email', 'hello@foundit.com'],
    ['bi-clock', 'Opening hours', 'Sunday to Thursday, 9:00 to 18:00 (GST)'],
  ];
@endphp
<x-page-banner :image="config('foundit.photos.contact')" icon="bi-envelope-heart" title="Contact us" subtitle="Questions, feedback or a safety concern? We read every message." />

<section class="section">
  <div class="container">
    <div class="row g-4 align-items-stretch">
      <div class="col-12 col-lg-5">
        <div class="fi-card fi-card-pad h-100 d-flex flex-column">
          <h2 class="h4 fw-bold">Get in touch</h2>
          <p class="text-secondary mb-0">Our team usually replies within one working day.</p>
          <ul class="contact-list">
            @foreach($info as [$icon, $title, $text])
              <li><span class="icon-tile"><i class="bi {{ $icon }}"></i></span><div><div class="fw-bold">{{ $title }}</div><div class="small text-secondary">{{ $text }}</div></div></li>
            @endforeach
          </ul>
          <div class="contact-note"><i class="bi bi-shield-check text-primary me-2"></i><strong>Safety concern?</strong> Mention the item title in your message and we will look into it first.</div>
        </div>
      </div>
      <div class="col-12 col-lg-7">
        <form class="fi-card fi-card-pad h-100 d-grid gap-3 align-content-start" method="POST" action="{{ route('contact.send') }}">
          @csrf
          <h2 class="h4 fw-bold mb-0">Send a message</h2>
          @include('partials.errors')
          <div class="row g-3">
            <div class="col-12 col-md-6"><label class="form-label fw-semibold" for="c-name">Name</label><input id="c-name" name="name" class="form-control" value="{{ old('name', auth()->user()?->name) }}" required></div>
            <div class="col-12 col-md-6"><label class="form-label fw-semibold" for="c-email">Email</label><input id="c-email" name="email" type="email" class="form-control" value="{{ old('email', auth()->user()?->email) }}" required></div>
          </div>
          <div><label class="form-label fw-semibold" for="c-subject">Subject</label><input id="c-subject" name="subject" class="form-control" value="{{ old('subject') }}"></div>
          <div><label class="form-label fw-semibold" for="c-message">Message</label><textarea id="c-message" name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea></div>
          <button class="btn btn-primary btn-lg" type="submit">Send message</button>
        </form>
      </div>
    </div>

    <div class="fi-card fi-card-pad mt-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h2 class="h4 fw-bold mb-1">Find us</h2><div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i>Al Maryah Island, Abu Dhabi, UAE</div></div>
        <a class="btn btn-outline-primary btn-sm" href="https://www.openstreetmap.org/?mlat=24.5033&amp;mlon=54.3950#map=15/24.5033/54.3950" target="_blank" rel="noreferrer"><i class="bi bi-map"></i> Open larger map</a>
      </div>
      <iframe class="map-frame" title="Map showing the FoundIt office on Al Maryah Island, Abu Dhabi" loading="lazy"
        src="https://www.openstreetmap.org/export/embed.html?bbox=54.3750%2C24.4950%2C54.4150%2C24.5150&amp;layer=mapnik&amp;marker=24.5033%2C54.3950"></iframe>
    </div>
  </div>
</section>
@endsection
