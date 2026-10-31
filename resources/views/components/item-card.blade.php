@props(['item'])
<article class="listing-card">
  <div class="listing-media">
    <x-img :src="$item->image_url" :alt="$item->title" />
    <span class="listing-type"><x-type-badge :type="$item->type" /></span>
  </div>
  <div class="listing-body">
    <div class="listing-meta">
      <span><i class="bi {{ config('foundit.category_icons.'.$item->category, 'bi-box-seam') }} me-1"></i>{{ $item->category }}</span>
      <x-status-badge :status="$item->status" />
    </div>
    <h3 class="listing-title"><a href="{{ route('items.show', $item) }}" class="stretched-link text-reset text-decoration-none">{{ $item->title }}</a></h3>
    <p class="listing-desc">{{ $item->description }}</p>
    <div class="listing-foot">
      <span><i class="bi bi-geo-alt me-1"></i>{{ $item->location }}</span>
      <span>{{ $item->date->format('j M Y') }}</span>
    </div>
  </div>
</article>
