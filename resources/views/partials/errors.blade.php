@if($errors->any())
  <x-notice kind="error" class="mb-3">
    @if($errors->count() === 1)
      {{ $errors->first() }}
    @else
      <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    @endif
  </x-notice>
@endif
