@props(['type'])
<span class="fi-badge type-{{ $type }}">
  <i class="bi {{ $type === 'lost' ? 'bi-search' : 'bi-hand-index-thumb' }}" aria-hidden="true"></i> {{ $type === 'lost' ? 'Lost' : 'Found' }}
</span>
