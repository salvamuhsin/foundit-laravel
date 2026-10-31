@extends('layouts.app')

@section('title', 'About | FoundIt')

@section('content')
@php
  $photos = config('foundit.photos');
  $values = [
    ['bi-heart', 'Kindness first', 'Most people want to do the right thing. We make it easy.'],
    ['bi-shield-lock', 'Privacy by default', 'Contact details stay private until you decide otherwise.'],
    ['bi-eye', 'Transparent decisions', 'Declines always show a reason, and records are never silently removed.'],
    ['bi-universal-access', 'For everyone', 'Responsive, accessible layouts with light and dark themes.'],
  ];
  $team = [['Sara Al Nuaimi', 'Founder'], ['Idris Mensah', 'Product'], ['Mei Tanaka', 'Design'], ['Yousef Karim', 'Community']];
  $timeline = [['2024', 'First lost wallet returned at a neighbourhood cafe.'], ['2025', 'Verification questions and private messaging launched.'], ['2026', 'Meetup confirmation and return tracking added.']];
@endphp
<x-page-banner :image="$photos['about']" icon="bi-info-circle" title="About FoundIt" subtitle="We help everyday kindness find its way back to the person who needs it." />

<section class="section">
  <div class="container"><div class="row g-4 g-lg-5 align-items-center">
    <div class="col-12 col-lg-6"><x-img :src="$photos['community']" alt="Friends handing back a lost item" class="split-img" /></div>
    <div class="col-12 col-lg-6">
      <h2 class="fw-bold">Our mission</h2>
      <p class="text-secondary">A lost wallet or a child’s toy is never just an object. FoundIt gives finders and owners one trusted place to meet, verify and return things safely.</p>
      <p class="text-secondary">Instead of posting everything in a public group, you keep key details private, and only the person who answers correctly moves forward.</p>
      @unless(auth()->check() && auth()->user()->isAdmin())<a href="{{ route('items.create') }}" class="btn btn-primary">Post a report</a>@endunless
    </div>
  </div></div>
</section>

<section class="section section-alt"><div class="container">
  <div class="section-title"><h2 class="fw-bold">What we believe</h2></div>
  <div class="row g-4">
    @foreach($values as [$icon, $title, $text])
      <div class="col-12 col-sm-6 col-lg-3"><div class="fi-card fi-card-pad h-100"><span class="icon-tile mb-3"><i class="bi {{ $icon }}"></i></span><h3 class="h6 fw-bold">{{ $title }}</h3><p class="small text-secondary mb-0">{{ $text }}</p></div></div>
    @endforeach
  </div>
</div></section>

<section class="section"><div class="container"><div class="row g-4 g-lg-5 align-items-center">
  <div class="col-12 col-lg-6 order-lg-2"><x-img :src="$photos['safety']" alt="A safe public handover" class="split-img" /></div>
  <div class="col-12 col-lg-6 order-lg-1">
    <h2 class="fw-bold">Our journey</h2>
    <ul class="list-unstyled d-grid gap-3 mb-0">
      @foreach($timeline as [$year, $text])
        <li class="d-flex gap-3"><span class="fi-badge type-found align-self-start">{{ $year }}</span><span class="text-secondary">{{ $text }}</span></li>
      @endforeach
    </ul>
  </div>
</div></div></section>

<section class="section section-alt"><div class="container">
  <div class="section-title"><h2 class="fw-bold">The people behind it</h2><p class="text-secondary mb-0">A small team that has lost plenty of umbrellas.</p></div>
  <div class="row g-3 row-cols-2 row-cols-lg-4">
    @foreach($team as [$name, $role])
      <div class="col"><div class="fi-card fi-card-pad text-center h-100"><span class="avatar mb-3">{{ collect(explode(' ', $name))->map(fn ($p) => mb_substr($p, 0, 1))->implode('') }}</span><div class="fw-bold">{{ $name }}</div><div class="small text-secondary">{{ $role }}</div></div></div>
    @endforeach
  </div>
</div></section>
@endsection
