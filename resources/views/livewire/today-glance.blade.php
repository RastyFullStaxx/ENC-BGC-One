<section id="availability" class="availability-preview py-5" wire:poll.30s>
  <div class="container">
    <div class="section-heading text-center mb-5">
      <span class="section-eyebrow">Quick availability glance</span>
      <h2>Today’s load at a glance</h2>
      <p class="workflow-subtext mb-0">Auto-updates every 30 seconds to reflect current time.</p>
    </div>

    <div class="row g-4 align-items-start">
      <div class="col-12">
        <article class="availability-panel availability-panel-schedule h-100" data-schedule-start="{{ $scheduleStart }}" data-schedule-end="{{ $scheduleEnd }}">
          <div class="availability-panel-head availability-panel-head-compact d-flex justify-content-between">
            <span>Last sync {{ $lastSync->format('g:i A') }} · {{ $lastSync->format('M j, Y') }}</span>
            <span class="text-muted small">Current time {{ $nowLabel }} · {{ config('app.timezone', 'UTC') }}</span>
          </div>
          <div class="schedule-scale">
            @for ($hour = $scheduleStart; $hour <= $scheduleEnd; $hour += 2)
              <span>{{ $hour <= 12 ? $hour : $hour - 12 }}{{ $hour < 12 ? ' AM' : ' PM' }}</span>
            @endfor
          </div>
          <div class="schedule-board">
            @forelse ($scheduleBlocks as $block)
              <div class="schedule-row">
                <div class="schedule-meta">
                  <p class="schedule-room">{{ $block['room'] }}</p>
                  <p class="schedule-status schedule-status-{{ $block['status'] ?? 'available' }}">{{ $block['status_label'] ?? ucwords(str_replace('-', ' ', $block['status'] ?? 'available')) }}</p>
                  <p class="schedule-note">{{ $block['note'] }}</p>
                </div>
                <div class="schedule-track">
                  @if ($nowInRange)
                    <span class="schedule-now" data-label="Now {{ $nowLabel }}" style="--now-offset: {{ $nowOffset }}%;" aria-hidden="true"></span>
                  @endif
                  @foreach ($block['segments'] ?? [] as $segment)
                    @php
                      $state = $segment['status'] ?? 'available';
                      $offset = (($segment['start'] - $scheduleStart) / $range) * 100;
                      $width = (($segment['end'] - $segment['start']) / $range) * 100;
                      $offset = max(0, min(100, $offset));
                      $width = max(1, min(100, $width));
                      $label = $stateLabels[$state] ?? ucwords($state);
                    @endphp
                    <span class="schedule-block schedule-block-{{ $state }}" style="--segment-offset: {{ $offset }}%; --segment-width: {{ $width }}%;" data-label="{{ $label }}" aria-label="{{ $label }}"></span>
                  @endforeach
                </div>
              </div>
            @empty
              <div class="schedule-row">
                <div class="schedule-meta">
                  <p class="schedule-room mb-1">No facilities yet</p>
                  <p class="schedule-note">Add a booking to see live availability.</p>
                </div>
                <div class="schedule-track d-flex align-items-center justify-content-center text-muted small">
                  <span>Nothing scheduled today</span>
                </div>
              </div>
            @endforelse
          </div>
          <div class="schedule-legend">
            <span class="legend-item legend-available"><i class="legend-dot dot-available"></i> Available</span>
            <span class="legend-item legend-limited"><i class="legend-dot dot-limited"></i> Limited availability</span>
            <span class="legend-item legend-occupied"><i class="legend-dot dot-occupied"></i> Occupied</span>
            <span class="legend-item legend-maintenance"><i class="legend-dot dot-maintenance"></i> Under maintenance</span>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
