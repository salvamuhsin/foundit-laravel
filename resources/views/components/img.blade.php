{{-- Image with a built-in fallback so layouts never show a broken icon. --}}
@props(['src' => null, 'alt' => ''])
<img src="{{ $src ?: asset('images/placeholder.svg') }}" alt="{{ $alt }}"
     loading="{{ $attributes->get('loading', 'lazy') }}"
     onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}'"
     {{ $attributes->except('loading') }}>
