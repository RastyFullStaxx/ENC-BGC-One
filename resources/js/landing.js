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
});

// --- Idle hide/show for header & footer ---
(function () {
  const header = document.querySelector('.header-slide');
  const footer = document.querySelector('.footer-slide');
  if (!header && !footer) return;

  let idleTimer = null;
  const IDLE_MS = 3000;

  const hideBars = () => {
    header && header.classList.add('header-hidden');
    footer && footer.classList.add('footer-hidden');
  };

  const showBars = () => {
    header && header.classList.remove('header-hidden');
    footer && footer.classList.remove('footer-hidden');
  };

  const resetTimer = () => {
    showBars();
    clearTimeout(idleTimer);
    idleTimer = setTimeout(hideBars, IDLE_MS);
  };

  // User activity that should reveal bars
  ['mousemove','mousedown','keydown','touchstart','scroll'].forEach(evt =>
    window.addEventListener(evt, resetTimer, { passive: true })
  );

  // start the timer once the page is ready
  window.addEventListener('load', resetTimer);
})();
