@extends('layouts.app')

@section('title', 'FoundIt | Lost & Found, Returned Safely')

@section('content')
@php
  $me = auth()->user();
  $isAdmin = $me && $me->isAdmin();
  $photos = config('foundit.photos');

  $steps = [
    ['bi-pencil-square', 'Post a report', 'Describe what you lost or found, add a photo and a question only the real owner could answer.'],
    ['bi-patch-check', 'Verify ownership', 'Claimants answer your question and add proof. You approve or decline with a clear reason.'],
    ['bi-chat-dots', 'Talk securely', 'Message inside FoundIt. No phone numbers or emails are shared before you choose to.'],
    ['bi-geo-alt', 'Meet and return', 'Agree a public place and time, confirm together, then mark the item returned.'],
  ];
  $stories = [
    ['Hessa A.', 'Lost passport holder', 'I posted at the airport and a stranger had already listed it. We met at the arrivals cafe the same evening.'],
    ['Marcus T.', 'Found camera', 'The verification question made it easy. Only the real owner knew the last photo on the card.'],
    ['Noor K.', 'Lost teddy bear', 'My daughter slept again that night. A kind finder confirmed details and we collected it the next morning.'],
  ];
  $faqs = [
    ['Is FoundIt free?', 'Yes. Posting, claiming and messaging are free for everyone.'],
    ['How do you stop people claiming things that are not theirs?', 'Each listing has a private verification question. Only the poster sees the answers and decides.'],
    ['Where should we meet?', 'Always in a busy public place such as a mall entrance, cafe or park gate. FoundIt asks both people to confirm the place and time.'],
    ['What happens if my claim is declined?', 'You see the reason the poster gave. Your record stays in your dashboard.'],
    ['Why is my account restricted?', 'Accounts can be restricted after repeated no-shows or misuse. You can still view existing records, and the reason is always shown.'],
  ];
  $statList = [
    [$stats['items'] ?? 0, 'Items listed'],
    [$stats['returned'] ?? 0, 'Returned to owners'],
    [$stats['claims'] ?? 0, 'Claims reviewed'],
    [$stats['members'] ?? 0, 'Community members'],
  ];
@endphp

<x-page-banner :tall="true" :image="$photos['hero']" title="Lost it? Found it? Let’s get it home." subtitle="Report lost and found items, verify ownership, message safely and arrange a public meetup.">
  <form class="hero-search d-flex flex-column flex-sm-row gap-1 mt-4" method="GET" action="{{ route('browse') }}" role="search">
    <label class="visually-hidden" for="hero-q">Search items</label>
    <input id="hero-q" name="q" class="form-control" placeholder="Search wallet, keys, phone...">
    <label class="visually-hidden" for="hero-type">Type</label>
    <select id="hero-type" name="type" class="form-select w-auto flex-shrink-0">
      <option value="">Lost &amp; found</option><option value="lost">Lost</option><option value="found">Found</option>
    </select>
    <button class="btn btn-primary px-4" type="submit"><i class="bi bi-search"></i> Search</button>
  </form>
  <div class="d-flex flex-wrap gap-2 mt-3">
    @unless($isAdmin)<a href="{{ route('items.create') }}" class="btn btn-accent"><i class="bi bi-plus-circle"></i> Report an item</a>@endunless
    @guest<a href="{{ route('register') }}" class="btn btn-outline-light">Create free account</a>@endguest
    @if($isAdmin)<a href="{{ route('admin.index') }}" class="btn btn-accent"><i class="bi bi-shield-lock"></i> Admin panel</a>@endif
  </div>
</x-page-banner>

<section class="container stat-strip" aria-label="Community numbers">
  <div class="fi-card fi-card-pad">
    <div class="row g-3 text-center">
      @foreach($statList as [$n, $label])
        <div class="col-6 col-md-3"><div class="stat-number">{{ number_format($n) }}</div><div class="small text-secondary">{{ $label }}</div></div>
      @endforeach
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-title"><h2 class="fw-bold">How FoundIt works</h2><p class="text-secondary mb-0">Four simple steps take an item from a stranger’s hands back to its owner.</p></div>
    <div class="row g-4">
      @foreach($steps as $i => [$icon, $title, $text])
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="fi-card fi-card-pad h-100">
            <div class="d-flex align-items-center gap-3 mb-3"><span class="icon-tile"><i class="bi {{ $icon }}"></i></span><span class="step-badge">{{ $i + 1 }}</span></div>
            <h3 class="h6 fw-bold">{{ $title }}</h3><p class="small text-secondary mb-0">{{ $text }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 section-title mb-4 mw-100">
      <div><h2 class="fw-bold">Recently posted</h2><p class="text-secondary mb-0">Fresh reports from around Abu Dhabi.</p></div>
      <a href="{{ route('browse') }}" class="btn btn-outline-primary">See all items</a>
    </div>
    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
      @forelse($recent as $item)
        <div class="col"><x-item-card :item="$item" /></div>
      @empty
        <div class="col-12"><div class="fi-card fi-card-pad text-center text-secondary">No reports yet.</div></div>
      @endforelse
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-title"><h2 class="fw-bold">Browse by category</h2><p class="text-secondary mb-0">Jump straight to the kind of item you are looking for.</p></div>
    <div class="row g-3 row-cols-3 row-cols-md-5 row-cols-lg-9">
      @foreach(config('foundit.categories') as $c)
        <div class="col">
          <a href="{{ route('browse', ['category' => $c]) }}" class="fi-card category-tile hover-lift h-100"><span class="icon-tile"><i class="bi {{ config('foundit.category_icons.'.$c) }}"></i></span>{{ $c }}</a>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="row g-4 g-lg-5 align-items-center">
      <div class="col-12 col-lg-6"><x-img :src="$photos['safety']" alt="Two people safely handing over a returned item" class="split-img" /></div>
      <div class="col-12 col-lg-6">
        <h2 class="fw-bold">Built for safe, honest returns</h2>
        <p class="text-secondary">Every listing includes a private verification question, so only the real owner or finder can get through.</p>
        <ul class="list-unstyled d-grid gap-3 mb-4">
          @foreach([['bi-shield-check', 'Ownership checks', 'Answers and details are visible only to the person who posted.'], ['bi-chat-square-lock', 'Private messaging', 'Talk inside FoundIt until you are comfortable.'], ['bi-people', 'Public meetups', 'Both people confirm the place and time before anything is handed over.']] as [$ic, $t, $d])
            <li class="d-flex gap-3"><span class="icon-tile"><i class="bi {{ $ic }}"></i></span><div><strong>{{ $t }}</strong><div class="small text-secondary">{{ $d }}</div></div></li>
          @endforeach
        </ul>
        <a href="{{ route('about') }}" class="btn btn-primary">Learn how we work</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-title"><h2 class="fw-bold">Stories from the community</h2><p class="text-secondary mb-0">Small acts of kindness, returned one item at a time.</p></div>
    <div class="row g-4">
      @foreach($stories as [$name, $item, $text])
        <div class="col-12 col-md-4">
          <figure class="fi-card fi-card-pad h-100 mb-0">
            <div class="quote-mark"><i class="bi bi-quote"></i></div>
            <blockquote class="mb-3">{{ $text }}</blockquote>
            <figcaption class="small"><strong>{{ $name }}</strong><span class="text-secondary"> &mdash; {{ $item }}</span></figcaption>
          </figure>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="row g-4">
      <div class="col-12 col-lg-6">
        <div class="fi-card fi-card-pad h-100"><h2 class="h4 fw-bold"><i class="bi bi-search text-primary me-2"></i>If you lost something</h2>
          <ul class="text-secondary mb-0 ps-3 d-grid gap-2"><li>Post quickly with a clear photo and the place you last had it.</li><li>Keep one detail private to use as your verification question.</li><li>Check the Browse page for found items near that place.</li></ul></div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="fi-card fi-card-pad h-100"><h2 class="h4 fw-bold"><i class="bi bi-hand-index-thumb text-primary me-2"></i>If you found something</h2>
          <ul class="text-secondary mb-0 ps-3 d-grid gap-2"><li>Do not share every detail in the public description.</li><li>Ask a question only the owner can answer.</li><li>Meet in public and never hand over an item without confirming.</li></ul></div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row g-4 g-lg-5">
      <div class="col-12 col-lg-4"><h2 class="fw-bold">Questions, answered</h2><p class="text-secondary">Still unsure? <a href="{{ route('contact') }}">Contact us</a>.</p></div>
      <div class="col-12 col-lg-8">
        @foreach($faqs as [$q, $a])
          <details class="faq"><summary>{{ $q }}<i class="bi bi-plus-lg flex-shrink-0"></i></summary><p class="text-secondary mt-2 mb-0">{{ $a }}</p></details>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="container pb-4">
  <div class="cta-band p-4 p-md-5 text-center">
    <h2 class="fw-bold">Ready to reunite someone with their belongings?</h2>
    <p class="mb-4 mx-auto lead-narrow">Create a free account in a minute and post your first report.</p>
    <div class="d-flex flex-wrap gap-2 justify-content-center">
      @guest<a href="{{ route('register') }}" class="btn btn-accent btn-lg">Create account</a>@endguest
      @if($me && !$isAdmin)<a href="{{ route('items.create') }}" class="btn btn-accent btn-lg">Report an item</a>@endif
      <a href="{{ route('browse') }}" class="btn btn-outline-light btn-lg">Browse items</a>
    </div>
  </div>
</section>
@endsection
