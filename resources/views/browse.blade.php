@extends('layouts.app')

@section('title', 'Browse items | FoundIt')

@section('content')
@php
  $f = request()->only(['q', 'type', 'category', 'status', 'sort']);
  $active = collect($f)->only(['q', 'type', 'category', 'status'])->filter()->count();
@endphp
<x-page-banner :image="config('foundit.photos.browse')" icon="bi-grid" title="Browse lost & found items" subtitle="Search every report, filter by category and open an item to submit a claim." />

<div class="container section pt-4 p-5">
  <div class="d-lg-none mb-3">
    <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filters" aria-controls="filters">
      <i class="bi bi-funnel"></i> Filters{{ $active ? " ({$active})" : '' }}
    </button>
  </div>

  <div class="collapse d-lg-block {{ $active ? 'show' : '' }}" id="filters">
    <form class="fi-card fi-card-pad mb-4" method="GET" action="{{ route('browse') }}">
      <div class="row g-3 align-items-end">
        <div class="col-12 col-lg-3">
          <label class="form-label fw-semibold" for="f-q">Search</label>
          <input id="f-q" name="q" class="form-control" placeholder="Title, description or place" value="{{ $f['q'] ?? '' }}">
        </div>
        <div class="col-6 col-lg-2">
          <label class="form-label fw-semibold" for="f-type">Type</label>
          <select id="f-type" name="type" class="form-select">
            <option value="">All</option>
            <option value="lost" @selected(($f['type'] ?? '') === 'lost')>Lost</option>
            <option value="found" @selected(($f['type'] ?? '') === 'found')>Found</option>
          </select>
        </div>
        <div class="col-6 col-lg-2">
          <label class="form-label fw-semibold" for="f-cat">Category</label>
          <select id="f-cat" name="category" class="form-select">
            <option value="">All</option>
            @foreach(config('foundit.categories') as $c)<option @selected(($f['category'] ?? '') === $c)>{{ $c }}</option>@endforeach
          </select>
        </div>
        <div class="col-6 col-lg-2">
          <label class="form-label fw-semibold" for="f-status">Status</label>
          <select id="f-status" name="status" class="form-select">
            <option value="">All</option>
            @foreach(['open' => 'Open', 'pending' => 'Claim pending', 'meetup' => 'Meetup arranged', 'returned' => 'Returned'] as $v => $l)
              <option value="{{ $v }}" @selected(($f['status'] ?? '') === $v)>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-lg-1">
          <label class="form-label fw-semibold" for="f-sort">Sort</label>
          <select id="f-sort" name="sort" class="form-select">
            <option value="new" @selected(($f['sort'] ?? 'new') === 'new')>Newest</option>
            <option value="old" @selected(($f['sort'] ?? '') === 'old')>Oldest</option>
          </select>
        </div>
        <div class="col-12 col-lg-2 d-grid"><button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Apply</button></div>
      </div>
    </form>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0 text-secondary" aria-live="polite">{{ $items->total() }} {{ Str::plural('item', $items->total()) }} found</p>
    @if($active)<a class="btn btn-link btn-sm" href="{{ route('browse') }}">Clear filters</a>@endif
  </div>

  @if($items->count())
    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-xl-3 ">
      @foreach($items as $item)<div class="col"><x-item-card :item="$item" /></div>@endforeach
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
  @else
    <div class="fi-card fi-card-pad text-center py-5">
      <i class="bi bi-binoculars display-5 text-primary"></i>
      <h2 class="h5 mt-3">No items match those filters</h2>
      <p class="text-secondary">Try fewer filters, or post a report so others can find it.</p>
      @unless(auth()->check() && auth()->user()->isAdmin())<a href="{{ route('items.create') }}" class="btn btn-primary">Report an item</a>@endunless
    </div>
  @endif
</div>
@endsection
