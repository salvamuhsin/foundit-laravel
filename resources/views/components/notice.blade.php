@props(['kind' => 'error'])
@php $icon = ['ok' => 'bi-check-circle-fill', 'warn' => 'bi-exclamation-triangle-fill'][$kind] ?? 'bi-x-octagon-fill'; @endphp
<div {{ $attributes->merge(['class' => "fi-notice notice-{$kind}", 'role' => $kind === 'error' ? 'alert' : 'status']) }}>
  <i class="bi {{ $icon }} flex-shrink-0" aria-hidden="true"></i>
  <div>{{ $slot }}</div>
</div>
