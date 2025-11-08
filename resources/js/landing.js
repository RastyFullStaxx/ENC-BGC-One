// resources/js/landing.js
import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
  /* Smooth scroll for same-page anchors (e.g., #features) */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      const el = document.querySelector(id);
      if (!el) return;
      e.preventDefault();
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      // Move focus for accessibility
      el.setAttribute('tabindex', '-1');
      el.focus({ preventScroll: true });
    });
  });

  /* Elevate header when scrolling (subtle shadow) */
  const header = document.querySelector('.site-header');
  if (header){
    const onScroll = () => {
      const y = window.scrollY || document.documentElement.scrollTop;
      header.classList.toggle('elevated', y > 4);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* Show focus rings only when tabbing with keyboard */
  const addFocusRing = () => document.documentElement.classList.add('user-is-tabbing');
  const removeFocusRing = () => document.documentElement.classList.remove('user-is-tabbing');
  window.addEventListener('keydown', (e) => { if (e.key === 'Tab') addFocusRing(); }, { once: true });
  window.addEventListener('mousedown', removeFocusRing);

  /* Lightweight analytics hook for CTAs (replace with real tracker later) */
  document.querySelectorAll('[data-analytics]').forEach(el => {
    el.addEventListener('click', () => {
      // eslint-disable-next-line no-console
      console.debug('analytics:event', el.getAttribute('data-analytics'));
    });
  });

  /* Optional: mobile nav toggle if your partial includes .nav-toggle */
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav');
  if (toggle && nav){
    toggle.addEventListener('click', () => {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      nav.classList.toggle('open');
    });
  }

  initIdleHeaderFooter();
});

function initIdleHeaderFooter(){
  const header = document.querySelector('.header-slide');
  const footer = document.querySelector('.footer-slide');
  if (!header && !footer) return;

  let idleTimer = null;
  const IDLE_MS = 3000;

  const hideBars = () => {
    if (header) header.classList.add('header-hidden');
    if (footer) footer.classList.add('footer-hidden');
  };

  const showBars = () => {
    if (header) header.classList.remove('header-hidden');
    if (footer) footer.classList.remove('footer-hidden');
  };

  const resetTimer = () => {
    showBars();
    clearTimeout(idleTimer);
    idleTimer = window.setTimeout(hideBars, IDLE_MS);
  };

  const activityEvents = ['mousemove','mousedown','keydown','touchstart','scroll'];
  activityEvents.forEach(evt => {
    const options = (evt === 'scroll' || evt === 'touchstart' || evt === 'mousemove') ? { passive: true } : false;
    window.addEventListener(evt, resetTimer, options);
  });

  window.addEventListener('load', resetTimer, { once: true });
  resetTimer();
}

// Smooth anchor scroll for same-page links
document.addEventListener('click', (e) => {
  const a = e.target.closest('a[href^="#"]');
  if (!a) return;
  const id = a.getAttribute('href');
  const target = document.querySelector(id);
  if (!target) return;
  e.preventDefault();
  target.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// Animate progress bars when visible (prefers-reduced-motion aware)
(() => {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const container = document.querySelector('#availability');
  if (!container) return;

  const bars = [...container.querySelectorAll('.progress-bar')];
  const originalWidths = bars.map(b => b.style.width);

  // start at 0%
  bars.forEach(b => (b.style.width = '0%'));

  const io = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      // restore widths to trigger CSS transition
      bars.forEach((b, i) => requestAnimationFrame(() => (b.style.width = originalWidths[i])));
      obs.disconnect();
    });
  }, { threshold: 0.25 });

  io.observe(container);
})();

// Refill the heat bars each time the section scrolls into view
(() => {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const section = document.querySelector('#availability');
  if (!section) return;

  const bars = [...section.querySelectorAll('.heatbar')];
  const targets = bars.map(b => b.getAttribute('data-width') || b.style.width || '0%');

  // Start empty so the first entry animates
  bars.forEach(b => (b.style.width = '0%'));

  // Helper fns (RAF to keep it jank-free)
  const fill  = () => bars.forEach((b, i) => requestAnimationFrame(() => (b.style.width = targets[i])));
  const empty = () => bars.forEach(b => requestAnimationFrame(() => (b.style.width = '0%')));

  // Observer: fill on enter, empty on exit so it re-animates next time
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.target !== section) return;
      if (entry.isIntersecting) {
        fill();
      } else {
        // Only empty when the section is fully out to avoid flicker
        // (intersectionRatio is 0 when it fully leaves)
        if (entry.intersectionRatio === 0) empty();
      }
    });
  }, {
    root: null,
    threshold: 0.25,        // need ~25% of the section visible to trigger
    rootMargin: '0px 0px -10% 0px'
  });

  io.observe(section);
})();

