@props(['title', 'subtitle' => null, 'image' => null, 'icon' => null, 'tall' => false])
<header class="page-banner {{ $tall ? 'page-banner-tall' : '' }}" @if($image) style="--banner-image: url('{{ $image }}')" @endif>
  <div class="container position-relative">
    <div class="banner-content">
      @if($icon)<span class="banner-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>@endif
      <h1 class="banner-title">{{ $title }}</h1>
      @if($subtitle)<p class="banner-subtitle">{{ $subtitle }}</p>@endif
      {{ $slot }}
    </div>
  </div>
</header>
