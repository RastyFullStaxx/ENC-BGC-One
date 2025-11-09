/* resources/js/wizard.js
   Booking Wizard interactions — vanilla JS, Bootstrap friendly
*/
(() => {

  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const $  = (sel, root = document) => root.querySelector(sel);

  const form = $('#bookingForm');
  if (!form) return; // only run on the wizard page

  // --- Elements
  const steps = $$('.wizard-step');               // fieldsets (step-1..3)
  const progressEl = $('#wizardProgress');
  const stepTitle = $('#wizardStepTitle');
  const stepHelp  = $('#wizardStepHelp');
  const stepCounter = $('#wizardStepCounter');
  const crumb = $('.wizard-stepper .breadcrumb');

  const summary = $('#summary');

  // Step metadata (title + helper)
  const META = {
    1: { title: '1. Choose Resource', help: 'Pick date, time and a room.' },
    2: { title: '2. Booking Details', help: 'Tell us what you need — we’ll prep the room.' },
    3: { title: '3. Review & Confirm', help: 'Verify details and accept policies.' }
  };

  // Helpers to convert values to nice labels
  const durationLabel = (mins) => {
    const n = parseInt(mins, 10);
    if (!n || n <= 0) return '—';
    if (n % 60 === 0) return `${n/60} hour${n===60? '':''}`;
    if (n === 90) return '1.5 hours';
    return `${n} mins`;
  };

  const multiselectLabel = (select) => {
    const vals = $$('.form-select option:checked', select).map(o => o.textContent.trim()).filter(Boolean);
    return vals.length ? vals.join(', ') : 'None';
  };

  // Keep current step state
  let stepIndex = 0; // 0-based

  // -------- Navigation
  const showStep = (i) => {
    stepIndex = Math.max(0, Math.min(steps.length - 1, i));

    steps.forEach((fs, idx) => {
      const active = idx === stepIndex;
      fs.classList.toggle('d-none', !active);
      fs.setAttribute('aria-hidden', String(!active));
    });

    const human = stepIndex + 1;
    const meta = META[human];

    // Progress (33/66/100)
    const pct = Math.round(((human) / steps.length) * 100);
    progressEl.style.width = `${pct}%`;
    progressEl.setAttribute('aria-valuenow', String(pct));

    // Titles
    stepTitle.textContent = meta.title;
    stepHelp.textContent  = meta.help;
    stepCounter.textContent = `Step ${human} of ${steps.length}`;

    // Breadcrumb badges
    if (crumb) {
      const items = $$('.breadcrumb-item', crumb);
      items.forEach((li, idx) => {
        const badge = $('.badge', li);
        if (!badge) return;
        if (idx < stepIndex) {
          badge.className = 'badge rounded-pill text-bg-primary';
          li.classList.remove('active');
        } else if (idx === stepIndex) {
          badge.className = 'badge rounded-pill text-bg-primary';
          li.classList.add('active');
        } else {
          badge.className = 'badge rounded-pill text-bg-light border';
          li.classList.remove('active');
        }
      });
    }

    // Move focus to first interactive field in the step for keyboarders
    const focusable = $('input,select,textarea,button', steps[stepIndex]);
    if (focusable) focusable.focus({ preventScroll: true });

    // Refresh review bindings when entering step 3
    if (human === 3) bindAll();
  };

  const next = () => {
    if (!validateStep(stepIndex)) return;
    showStep(stepIndex + 1);
  };
  const prev = () => showStep(stepIndex - 1);

  // Buttons
  $$('[data-next]').forEach(btn => btn.addEventListener('click', next));
  $$('[data-prev]').forEach(btn => btn.addEventListener('click', prev));
  $$('[data-jump]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const target = parseInt(btn.getAttribute('data-jump'), 10) - 1;
      showStep(target);
    });
  });

  // -------- Validation per step
  const step1 = $('#step-1');
  const step2 = $('#step-2');
  const step3 = $('#step-3');

  function validateStep(idx) {
    if (idx === 0) return validateStep1();
    if (idx === 1) return validateStep2();
    if (idx === 2) return validateStep3();
    return true;
  }

  function validateStep1() {
    // HTML5 validation pass
    if (!step1.checkValidity()) {
      step1.classList.add('was-validated');
      return false;
    }
    // room type selected?
    const typeChosen = $('input[name="room_type"]:checked', step1);
    const typeErr = step1.querySelector('[data-error-roomtype]');
    if (!typeChosen) {
      if (typeErr) typeErr.style.display = '';
      return false;
    } else if (typeErr) typeErr.style.display = 'none';

    // room selected?
    const roomChosen = $('input[name="room_id"]:checked', step1);
    if (!roomChosen) {
      alert('Please select a room from the list.');
      return false;
    }

    return true;
  }

  function validateStep2() {
    // No required fields here except standard controls
    if (!step2.checkValidity()) {
      step2.classList.add('was-validated');
      return false;
    }
    return true;
  }

  function validateStep3() {
    if (!step3.checkValidity()) {
      step3.classList.add('was-validated');
      return false;
    }
    return true;
  }

  // --------- Live Summary Bindings
  const bindMap = {
    date:      () => $('#date')?.value || '—',
    start_time:() => $('#start_time')?.value || '—',
    duration_label:() => durationLabel($('#duration')?.value),
    capacity:  () => $('#capacity')?.value || '—',
    room_name: () => $('input[name="room_id"]:checked')?.closest('.room-card')?.querySelector('.card-title')?.textContent?.trim() || '—',
    layout_label:() => $('#layout')?.selectedOptions?.[0]?.text || 'Standard',
    extras_label:() => multiselectLabel($('#equipment')),
    purpose:   () => $('#purpose')?.value || '—'
  };

  function bindAll() {
    Object.entries(bindMap).forEach(([key, fn]) => {
      $$(`[data-bind="${key}"]`, summary.parentElement).forEach(el => {
        el.textContent = fn();
      });
    });
  }

  // Keep summary hot while typing/changing
  ['change','input'].forEach(evt => form.addEventListener(evt, bindAll));

  // Duration shows label in summary immediately
  $('#duration')?.addEventListener('change', bindAll);

  // --------- Rooms: choose, sort, filter
  const roomsGrid = $('#roomsGrid');
  const sortRooms = $('#sortRooms');

  // Clicking a room card's radio will also select via card click
  $$('.room-card').forEach(card => {
    card.addEventListener('click', (e) => {
      const radio = $('.choose-room', card);
      if (!radio) return;
      // Ignore when clicking links/controls inside
      if (e.target.closest('input,button,label,select')) return;
      radio.checked = true;
      // Visual focus for accessibility
      radio.dispatchEvent(new Event('change', { bubbles: true }));
    });
  });

  // Filter rooms based on attendees (capacity) as user types
  const capacityInput = $('#capacity');
  function filterRooms() {
    const need = parseInt(capacityInput.value || '0', 10);
    let shown = 0;
    $$('.room-card', roomsGrid).forEach(card => {
      const cap = parseInt(card.getAttribute('data-capacity') || '0', 10);
      const ok = !need || cap >= need;
      card.parentElement.classList.toggle('d-none', !ok);
      if (ok) shown++;
    });
    if (roomsGrid) {
      const emptyText = roomsGrid.getAttribute('data-empty-text') || 'No rooms available.';
      let msg = $('#roomsEmptyMsg', roomsGrid);
      if (shown === 0) {
        if (!msg) {
          msg = document.createElement('div');
          msg.id = 'roomsEmptyMsg';
          msg.className = 'text-center text-secondary small py-3';
          msg.textContent = emptyText;
          roomsGrid.appendChild(msg);
        }
      } else if (msg) {
        msg.remove();
      }
    }
  }
  capacityInput?.addEventListener('input', filterRooms);

  function sortRoomsNow() {
    if (!roomsGrid) return;
    const cards = $$('.col-12.col-md-6', roomsGrid).filter(col => !col.classList.contains('d-none'));
    const key = sortRooms?.value || 'soonest';

    const byName = (a,b) => {
      const an = $('.card-title', a).textContent.trim().toLowerCase();
      const bn = $('.card-title', b).textContent.trim().toLowerCase();
      return an.localeCompare(bn);
    };
    const byCap = (a,b) => {
      const ac = parseInt($('.room-card', a).getAttribute('data-capacity')||'0',10);
      const bc = parseInt($('.room-card', b).getAttribute('data-capacity')||'0',10);
      return ac - bc;
    };
    // 'soonest' is placeholder; without per-room times we default to name
    const comparator = key === 'capacity' ? byCap : byName;

    cards.sort(comparator).forEach(col => roomsGrid.appendChild(col));
  }
  sortRooms?.addEventListener('change', sortRoomsNow);

  // -------- Form submit
  form.addEventListener('submit', (e) => {
    // Validate all before real submit
    if (!validateStep(0) || !validateStep(1) || !validateStep(2)) {
      e.preventDefault();
      // Move user to the first failing step
      if (!validateStep(0)) showStep(0);
      else if (!validateStep(1)) showStep(1);
      else showStep(2);
      return;
    }
    // Normal submit: server handles success -> redirect/flash
    // If you want a client-side demo success, enable below:
    // e.preventDefault(); showSuccess();
  });

  function showSuccess() {
    $$('#step-1,#step-2,#step-3').forEach(s => s.classList.add('d-none'));
    $('#step-success')?.classList.remove('d-none');
    progressEl.style.width = '100%';
    stepTitle.textContent = 'Success';
    stepHelp.textContent  = 'Your booking has been created.';
    stepCounter.textContent = 'Done';
  }

  // -------- Init
  filterRooms();
  sortRoomsNow();
  bindAll();
  showStep(0);

})();
