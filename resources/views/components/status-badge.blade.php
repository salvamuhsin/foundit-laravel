@props(['status'])
@php [$label, $cls, $icon] = config('foundit.statuses.'.$status) ?? config('foundit.statuses.open'); @endphp
<span class="fi-badge {{ $cls }}"><i class="bi {{ $icon }}" aria-hidden="true"></i> {{ $label }}</span>
