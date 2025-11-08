<footer class="border-top py-3">
  <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
    <nav class="nav">
      @foreach(($links ?? []) as $link)
        <a class="nav-link px-0 me-3 text-secondary" href="{{ $link['href'] }}">{{ $link['label'] }}</a>
      @endforeach
    </nav>
    <p class="mb-0 text-secondary small">{{ $copyright ?? ('© '.date('Y').' ENC BGC One') }}</p>
  </div>
</footer>
