/* resources/js/wizard.js
   Sequential Booking Wizard — progressive disclosure, autosave, bindings
*/
(() => {
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));
  const $  = (s, r=document) => r.querySelector(s);
  const form = $('#bookingForm');
  if (!form) return;

  // ---- Elements
  const steps = $$('.wizard-step');
  const progressEl = $('#wizardProgress');
  const stepTitle = $('#wizardStepTitle');
  const stepHelp  = $('#wizardStepHelp');
  const stepCounter = $('#wizardStepCounter');
  const crumb = $('.wizard-nav .breadcrumb');

  // Ask blocks (mini questions) are handled *within* each step by data-ask indexing.
  // Step labels
  const META = {
    1: { title: 'Step 1 — Smart Room Finder', help: 'Answer a few quick questions.' },
    2: { title: 'Step 2 — Date & Time', help: 'Pick a date/time; recurrence optional.' },
    3: { title: 'Step 3 — Purpose & Details', help: 'Tell us the essentials.' },
    4: { title: 'Step 4 — Attendees', help: 'Invite people and choose visibility.' },
    5: { title: 'Step 5 — Review & Policies', help: 'Check details and submit.' }
  };

  // Local storage key for autosave
  const STORE_KEY = 'enc_booking_wizard_draft';

  // ---------- UTIL
  const durationLabel = (mins) => {
    const n = parseInt(mins, 10);
    if (!n) return '—';
    const h = Math.floor(n/60), m = n%60;
    if (m===0) return `${h} hour${h>1?'s':''}`;
    return `${h>0?`${h}h `:''}${m}m`;
  };
  const multiselectLabel = (select) => {
    if (!select) return 'None';
    const vals = Array.from(select.selectedOptions).map(o=>o.textContent.trim());
    return vals.length ? vals.join(', ') : 'None';
  };
  const setEnterAnim = (el) => {
    el.classList.add('ask-enter');
    requestAnimationFrame(()=> el.classList.add('ask-enter-active'));
    setTimeout(()=> el.classList.remove('ask-enter','ask-enter-active'), 260);
  };

  // ---------- STEP NAV
  let stepIndex = 0; // 0..4

  const showStep = (i) => {
    stepIndex = Math.max(0, Math.min(steps.length-1, i));
    steps.forEach((fs, idx) => {
      const active = idx === stepIndex;
      fs.classList.toggle('d-none', !active);
      fs.setAttribute('aria-hidden', String(!active));
    });

    const human = stepIndex+1, meta = META[human];
    const pct = Math.round(((human) / steps.length) * 100);
    progressEl.style.width = `${pct}%`;
    progressEl.setAttribute('aria-valuenow', String(pct));
    stepTitle.textContent = meta.title;
    stepHelp.textContent  = meta.help;
    stepCounter.textContent = `Step ${human} of ${steps.length}`;

    if (crumb){
      const items = $$('.breadcrumb-item', crumb);
      items.forEach((li, idx) => {
        const badge = $('.badge', li);
        if (!badge) return;
        if (idx <= stepIndex){
          badge.className = 'badge rounded-pill text-bg-primary';
          li.classList.toggle('active', idx===stepIndex);
        } else {
          badge.className = 'badge rounded-pill text-bg-light border';
          li.classList.remove('active');
        }
      });
    }

    // Focus first interactive control of the first visible ask in the step
    const firstAsk = steps[stepIndex].querySelector('.ask:not(.d-none) input, .ask:not(.d-none) select, .ask:not(.d-none) textarea, .ask:not(.d-none) .btn');
    firstAsk?.focus({ preventScroll:true });

    // When entering Review step, refresh all bindings
    if (human === 5) bindAll();
  };

  const nextStep = () => showStep(stepIndex+1);
  const prevStep = () => showStep(stepIndex-1);

  // Step buttons (Next/Prev across steps)
  $$('[data-next]').forEach(b => b.addEventListener('click', () => {
    if (!validateCurrentStep()) return;
    nextStep(); saveDraft();
  }));
  $$('[data-prev]').forEach(b => b.addEventListener('click', () => { prevStep(); saveDraft(); }));

  // ---------- ASK FLOW (within steps)
  function showNextAsk(container, fromAsk){
    const asks = $$('.ask', container);
    if (!asks.length) return;

    let idx = fromAsk ? asks.indexOf(fromAsk) : -1;
    if (idx === -1){
      for (let i = asks.length - 1; i >= 0; i--){
        if (!asks[i].classList.contains('d-none')){
          idx = i;
          break;
        }
      }
    }

    const next = asks[idx+1];
    if (!next) return;

    next.classList.remove('d-none');
    next.setAttribute('aria-hidden','false');
    setEnterAnim(next);
    // focus first control in the next ask
    const f = next.querySelector('input,select,textarea,button');
    f?.focus({ preventScroll:true });
  }

  // Per-ask "Next" buttons
  $$('[data-next-ask]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const ask = e.currentTarget.closest('.ask');
      if (!ask) return;
      if (!validateAsk(ask)) return;
      const fs = e.currentTarget.closest('.wizard-step');
      showNextAsk(fs, ask);
      saveDraft();
    });
  });

  // Step 1 actions: find rooms / manual
  const finderResults = $('#finderResults');
  const manualPicker  = $('#manualPicker');
  $$('[data-find-rooms]').forEach(btn => btn.addEventListener('click', () => {
    // Validate the last ask in Step 1 if needed
    const step1 = $('#step-1');
    const asks = $$('.ask', step1);
    if (!asks.every(validateAsk)) return;

    manualPicker?.classList.add('d-none');
    finderResults?.classList.remove('d-none');
    setEnterAnim(finderResults);
    // Mock filter pass can be done here later
    saveDraft();
  }));
  $$('[data-manual-rooms]').forEach(btn => btn.addEventListener('click', () => {
    finderResults?.classList.add('d-none');
    manualPicker?.classList.remove('d-none');
    setEnterAnim(manualPicker);
    saveDraft();
  }));
  $$('[data-edit-answers]').forEach(btn => btn.addEventListener('click', () => {
    // Hide result sections and show first ask again
    finderResults?.classList.add('d-none');
    manualPicker?.classList.add('d-none');
    // reveal Q1 only, hide others
    const s1 = $('#step-1');
    const asks = $$('.ask', s1);
    asks.forEach((a, idx) => {
      a.classList.toggle('d-none', idx !== 0);
      a.setAttribute('aria-hidden', String(idx !== 0));
    });
  }));

  // ---------- VALIDATION
  function validateAsk(askEl){
    // Use native validity of inputs inside this ask (only the ones visible)
    const ctrls = $$('input,select,textarea', askEl).filter(el => !el.closest('.d-none'));
    let ok = true;

    ctrls.forEach(el => {
      if (el.type === 'radio'){
        const group = $$(`input[type="radio"][name="${el.name}"]`, askEl);
        const checked = group.some(r => r.checked);
        const err = askEl.querySelector('[data-error="room_type"]');
        if (group.length && !checked){
          err && (err.style.display = '');
          ok = false;
        } else if (err) err.style.display = 'none';
      } else {
        if (!el.checkValidity()){
          el.classList.add('is-invalid');
          ok = false;
        } else {
          el.classList.remove('is-invalid');
        }
      }
    });
    return ok;
  }

  function validateCurrentStep(){
    const fs = steps[stepIndex];
    // ensure any visible ask is valid
    const visibleAsks = $$('.ask', fs).filter(a => !a.classList.contains('d-none'));
    return visibleAsks.every(validateAsk);
  }

  // ---------- ROOM CARD UX
  const chooseRoomRadios = $$('input.choose-room');
  chooseRoomRadios.forEach(r => {
    r.addEventListener('change', () => { bindAll(); saveDraft(); });
  });
  $$('.room-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('input,label,button,select,textarea')) return;
      const radio = $('.choose-room', card);
      if (!radio) return;
      radio.checked = true;
      radio.dispatchEvent(new Event('change', { bubbles:true }));
    });
  });

  // ---------- DATE/TIME step: recurrence toggle + conflict mock
  const repeatToggle = $('#repeatToggle');
  const repeatOptions = $('#repeatOptions');
  repeatToggle?.addEventListener('change', () => {
    if (repeatToggle.checked){
      repeatOptions.classList.remove('d-none'); setEnterAnim(repeatOptions);
    } else {
      repeatOptions.classList.add('d-none');
    }
    saveDraft();
  });

  const dateInput = $('#date');
  const startInput = $('#start_time');
  const endInput   = $('#end_time');
  const conflictBlock = $('#conflictBlock');

  function mockConflictCheck(){
    // Demo: conflict if start between 11:00 and 14:00
    if (!startInput || !endInput) return;
    const s = startInput.value;
    if (s && s >= '11:00' && s < '14:00'){
      conflictBlock?.classList.remove('d-none');
    } else {
      conflictBlock?.classList.add('d-none');
    }
  }
  [dateInput, startInput, endInput].forEach(el => el?.addEventListener('change', () => {
    mockConflictCheck(); bindAll(); saveDraft();
  }));

  // ---------- SUMMARY BINDINGS
  const summaryRoot = $('#summary')?.parentElement || document;
  const bindMap = {
    room_name: () => $('input[name="room_id"]:checked')?.closest('.room-card')?.querySelector('.card-title')?.textContent?.trim() || '—',
    capacity:  () => $('#capacity')?.value || $('#attendees')?.value || '—',
    date:      () => $('#date')?.value || '—',
    start_time:() => $('#start_time')?.value || '—',
    end_time:  () => $('#end_time')?.value || '—',
    layout_label: () => $('#layout')?.selectedOptions?.[0]?.text || 'Standard',
    attendees_label: () => {
      const a = $('#attendees')?.value || '';
      return a ? `${a} attendee${parseInt(a,10)>1?'s':''}` : '—';
    },
    manpower_label: () => {
      const n = parseInt($('#manpower_count')?.value || '0',10);
      const role = $('#manpower_role')?.selectedOptions?.[0]?.text || 'General Support';
      return n>0 ? `${n} × ${role}` : 'None';
    },
    visibility_label: () => $('input[name="visibility"]:checked')?.value === 'public' ? 'Public (internal)' : 'Private',
    status_label: () => 'Pending Approval'
  };

  function bindAll(){
    Object.entries(bindMap).forEach(([k,fn]) => {
      $$(`[data-bind="${k}"]`, summaryRoot).forEach(el => el.textContent = fn());
      $$(`#reviewSummary [data-bind="${k}"]`).forEach(el => el.textContent = fn());
    });
  }

  // Keep summary hot
  form.addEventListener('input', () => { bindAll(); saveDraft(); });
  form.addEventListener('change', () => { bindAll(); saveDraft(); });

  // ---------- AUTOSAVE (localStorage)
  function saveDraft(){
    const data = Object.fromEntries(new FormData(form).entries());
    // include multi-value fields
    data['buildings[]'] = $$('input[name="buildings[]"]:checked').map(el=>el.value);
    data['equipment[]'] = $$('input[name="equipment[]"]:checked').map(el=>el.value);
    localStorage.setItem(STORE_KEY, JSON.stringify(data));
  }
  function loadDraft(){
    const raw = localStorage.getItem(STORE_KEY);
    if (!raw) return;
    try{
      const data = JSON.parse(raw);
      Object.entries(data).forEach(([k,v]) => {
        const els = $$(`[name="${k}"]`);
        if (!els.length) return;
        if (Array.isArray(v)){
          els.forEach(el => el.checked = v.includes(el.value));
        } else {
          const el = els[0];
          if (el.type === 'radio' || el.type === 'checkbox'){
            els.forEach(r => r.checked = (r.value == v));
          } else {
            el.value = v;
          }
        }
      });
    }catch(e){}
  }

  // ---------- FORM SUBMIT
  form.addEventListener('submit', (e) => {
    // Validate visible asks in current step and the agreement
    if (!validateCurrentStep()){
      e.preventDefault(); return;
    }
    const agree = $('#agree');
    if (agree && !agree.checked){
      e.preventDefault();
      agree.classList.add('is-invalid');
      showStep(4);
      return;
    }
    // Allow normal submit; to demo client-side success uncomment below:
    // e.preventDefault(); showSuccess();
  });

  function showSuccess(){
    $$('#step-1,#step-2,#step-3,#step-4,#step-5').forEach(s => s.classList.add('d-none'));
    $('#step-success')?.classList.remove('d-none');
    progressEl.style.width = '100%';
    stepTitle.textContent = 'Done — Request Sent';
    stepHelp.textContent  = 'We’ll notify you once it’s approved.';
    stepCounter.textContent = 'Completed';
    localStorage.removeItem(STORE_KEY);
  }

  // ---------- INIT
  loadDraft();
  // Ensure only the first ask of each step is visible initially (unless draft restored)
  steps.forEach(step => {
    const asks = $$('.ask', step);
    if (!asks.length) return;
    const anyFilled = asks.some(a => $$('input,select,textarea', a).some(ctrl => {
      if (ctrl.type === 'radio' || ctrl.type === 'checkbox') return ctrl.checked;
      return !!ctrl.value;
    }));
    if (!anyFilled){
      asks.forEach((a, idx) => {
        a.classList.toggle('d-none', idx!==0);
        a.setAttribute('aria-hidden', String(idx!==0));
      });
    }
  });

  bindAll();
  showStep(0);
  mockConflictCheck();
})();
/* resources/js/wizard.js
   Sequential Booking Wizard — progressive disclosure, autosave, bindings
*/
(() => {
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));
  const $  = (s, r=document) => r.querySelector(s);
  const form = $('#bookingForm');
  if (!form) return;

  // ---- Elements
  const steps = $$('.wizard-step');
  const progressEl = $('#wizardProgress');
  const stepTitle = $('#wizardStepTitle');
  const stepHelp  = $('#wizardStepHelp');
  const stepCounter = $('#wizardStepCounter');
  const crumb = $('.wizard-nav .breadcrumb');

  // Ask blocks (mini questions) are handled *within* each step by data-ask indexing.
  // Step labels
  const META = {
    1: { title: 'Step 1 — Smart Room Finder', help: 'Answer a few quick questions.' },
    2: { title: 'Step 2 — Date & Time', help: 'Pick a date/time; recurrence optional.' },
    3: { title: 'Step 3 — Purpose & Details', help: 'Tell us the essentials.' },
    4: { title: 'Step 4 — Attendees', help: 'Invite people and choose visibility.' },
    5: { title: 'Step 5 — Review & Policies', help: 'Check details and submit.' }
  };

  // Local storage key for autosave
  const STORE_KEY = 'enc_booking_wizard_draft';

  // ---------- UTIL
  const durationLabel = (mins) => {
    const n = parseInt(mins, 10);
    if (!n) return '—';
    const h = Math.floor(n/60), m = n%60;
    if (m===0) return `${h} hour${h>1?'s':''}`;
    return `${h>0?`${h}h `:''}${m}m`;
  };
  const multiselectLabel = (select) => {
    if (!select) return 'None';
    const vals = Array.from(select.selectedOptions).map(o=>o.textContent.trim());
    return vals.length ? vals.join(', ') : 'None';
  };
  const setEnterAnim = (el) => {
    el.classList.add('ask-enter');
    requestAnimationFrame(()=> el.classList.add('ask-enter-active'));
    setTimeout(()=> el.classList.remove('ask-enter','ask-enter-active'), 260);
  };

  // ---------- STEP NAV
  let stepIndex = 0; // 0..4

  const showStep = (i) => {
    stepIndex = Math.max(0, Math.min(steps.length-1, i));
    steps.forEach((fs, idx) => {
      const active = idx === stepIndex;
      fs.classList.toggle('d-none', !active);
      fs.setAttribute('aria-hidden', String(!active));
    });

    const human = stepIndex+1, meta = META[human];
    const pct = Math.round(((human) / steps.length) * 100);
    progressEl.style.width = `${pct}%`;
    progressEl.setAttribute('aria-valuenow', String(pct));
    stepTitle.textContent = meta.title;
    stepHelp.textContent  = meta.help;
    stepCounter.textContent = `Step ${human} of ${steps.length}`;

    if (crumb){
      const items = $$('.breadcrumb-item', crumb);
      items.forEach((li, idx) => {
        const badge = $('.badge', li);
        if (!badge) return;
        if (idx <= stepIndex){
          badge.className = 'badge rounded-pill text-bg-primary';
          li.classList.toggle('active', idx===stepIndex);
        } else {
          badge.className = 'badge rounded-pill text-bg-light border';
          li.classList.remove('active');
        }
      });
    }

    // Focus first interactive control of the first visible ask in the step
    const firstAsk = steps[stepIndex].querySelector('.ask:not(.d-none) input, .ask:not(.d-none) select, .ask:not(.d-none) textarea, .ask:not(.d-none) .btn');
    firstAsk?.focus({ preventScroll:true });

    // When entering Review step, refresh all bindings
    if (human === 5) bindAll();
  };

  const nextStep = () => showStep(stepIndex+1);
  const prevStep = () => showStep(stepIndex-1);

  // Step buttons (Next/Prev across steps)
  $$('[data-next]').forEach(b => b.addEventListener('click', () => {
    if (!validateCurrentStep()) return;
    nextStep(); saveDraft();
  }));
  $$('[data-prev]').forEach(b => b.addEventListener('click', () => { prevStep(); saveDraft(); }));

  // ---------- ASK FLOW (within steps)
  function showNextAsk(container, fromAsk){
    const asks = $$('.ask', container);
    if (!asks.length) return;

    let idx = fromAsk ? asks.indexOf(fromAsk) : -1;
    if (idx === -1){
      for (let i = asks.length - 1; i >= 0; i--){
        if (!asks[i].classList.contains('d-none')){
          idx = i;
          break;
        }
      }
    }

    const next = asks[idx+1];
    if (!next) return;

    next.classList.remove('d-none');
    next.setAttribute('aria-hidden','false');
    setEnterAnim(next);
    // focus first control in the next ask
    const f = next.querySelector('input,select,textarea,button');
    f?.focus({ preventScroll:true });
  }

  // Per-ask "Next" buttons
  $$('[data-next-ask]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const ask = e.currentTarget.closest('.ask');
      if (!ask) return;
      if (!validateAsk(ask)) return;
      const fs = e.currentTarget.closest('.wizard-step');
      showNextAsk(fs, ask);
      saveDraft();
    });
  });

  // Step 1 actions: find rooms / manual
  const finderResults = $('#finderResults');
  const manualPicker  = $('#manualPicker');
  $$('[data-find-rooms]').forEach(btn => btn.addEventListener('click', () => {
    // Validate the last ask in Step 1 if needed
    const step1 = $('#step-1');
    const asks = $$('.ask', step1);
    if (!asks.every(validateAsk)) return;

    manualPicker?.classList.add('d-none');
    finderResults?.classList.remove('d-none');
    setEnterAnim(finderResults);
    // Mock filter pass can be done here later
    saveDraft();
  }));
  $$('[data-manual-rooms]').forEach(btn => btn.addEventListener('click', () => {
    finderResults?.classList.add('d-none');
    manualPicker?.classList.remove('d-none');
    setEnterAnim(manualPicker);
    saveDraft();
  }));
  $$('[data-edit-answers]').forEach(btn => btn.addEventListener('click', () => {
    // Hide result sections and show first ask again
    finderResults?.classList.add('d-none');
    manualPicker?.classList.add('d-none');
    // reveal Q1 only, hide others
    const s1 = $('#step-1');
    const asks = $$('.ask', s1);
    asks.forEach((a, idx) => {
      a.classList.toggle('d-none', idx !== 0);
      a.setAttribute('aria-hidden', String(idx !== 0));
    });
  }));

  // ---------- VALIDATION
  function validateAsk(askEl){
    // Use native validity of inputs inside this ask (only the ones visible)
    const ctrls = $$('input,select,textarea', askEl).filter(el => !el.closest('.d-none'));
    let ok = true;

    ctrls.forEach(el => {
      if (el.type === 'radio'){
        const group = $$(`input[type="radio"][name="${el.name}"]`, askEl);
        const checked = group.some(r => r.checked);
        const err = askEl.querySelector('[data-error="room_type"]');
        if (group.length && !checked){
          err && (err.style.display = '');
          ok = false;
        } else if (err) err.style.display = 'none';
      } else {
        if (!el.checkValidity()){
          el.classList.add('is-invalid');
          ok = false;
        } else {
          el.classList.remove('is-invalid');
        }
      }
    });
    return ok;
  }

  function validateCurrentStep(){
    const fs = steps[stepIndex];
    // ensure any visible ask is valid
    const visibleAsks = $$('.ask', fs).filter(a => !a.classList.contains('d-none'));
    return visibleAsks.every(validateAsk);
  }

  // ---------- ROOM CARD UX
  const chooseRoomRadios = $$('input.choose-room');
  chooseRoomRadios.forEach(r => {
    r.addEventListener('change', () => { bindAll(); saveDraft(); });
  });
  $$('.room-card').forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('input,label,button,select,textarea')) return;
      const radio = $('.choose-room', card);
      if (!radio) return;
      radio.checked = true;
      radio.dispatchEvent(new Event('change', { bubbles:true }));
    });
  });

  // ---------- DATE/TIME step: recurrence toggle + conflict mock
  const repeatToggle = $('#repeatToggle');
  const repeatOptions = $('#repeatOptions');
  repeatToggle?.addEventListener('change', () => {
    if (repeatToggle.checked){
      repeatOptions.classList.remove('d-none'); setEnterAnim(repeatOptions);
    } else {
      repeatOptions.classList.add('d-none');
    }
    saveDraft();
  });

  const dateInput = $('#date');
  const startInput = $('#start_time');
  const endInput   = $('#end_time');
  const conflictBlock = $('#conflictBlock');

  function mockConflictCheck(){
    // Demo: conflict if start between 11:00 and 14:00
    if (!startInput || !endInput) return;
    const s = startInput.value;
    if (s && s >= '11:00' && s < '14:00'){
      conflictBlock?.classList.remove('d-none');
    } else {
      conflictBlock?.classList.add('d-none');
    }
  }
  [dateInput, startInput, endInput].forEach(el => el?.addEventListener('change', () => {
    mockConflictCheck(); bindAll(); saveDraft();
  }));

  // ---------- SUMMARY BINDINGS
  const summaryRoot = $('#summary')?.parentElement || document;
  const bindMap = {
    room_name: () => $('input[name="room_id"]:checked')?.closest('.room-card')?.querySelector('.card-title')?.textContent?.trim() || '—',
    capacity:  () => $('#capacity')?.value || $('#attendees')?.value || '—',
    date:      () => $('#date')?.value || '—',
    start_time:() => $('#start_time')?.value || '—',
    end_time:  () => $('#end_time')?.value || '—',
    layout_label: () => $('#layout')?.selectedOptions?.[0]?.text || 'Standard',
    attendees_label: () => {
      const a = $('#attendees')?.value || '';
      return a ? `${a} attendee${parseInt(a,10)>1?'s':''}` : '—';
    },
    manpower_label: () => {
      const n = parseInt($('#manpower_count')?.value || '0',10);
      const role = $('#manpower_role')?.selectedOptions?.[0]?.text || 'General Support';
      return n>0 ? `${n} × ${role}` : 'None';
    },
    visibility_label: () => $('input[name="visibility"]:checked')?.value === 'public' ? 'Public (internal)' : 'Private',
    status_label: () => 'Pending Approval'
  };

  function bindAll(){
    Object.entries(bindMap).forEach(([k,fn]) => {
      $$(`[data-bind="${k}"]`, summaryRoot).forEach(el => el.textContent = fn());
      $$(`#reviewSummary [data-bind="${k}"]`).forEach(el => el.textContent = fn());
    });
  }

  // Keep summary hot
  form.addEventListener('input', () => { bindAll(); saveDraft(); });
  form.addEventListener('change', () => { bindAll(); saveDraft(); });

  // ---------- AUTOSAVE (localStorage)
  function saveDraft(){
    const data = Object.fromEntries(new FormData(form).entries());
    // include multi-value fields
    data['buildings[]'] = $$('input[name="buildings[]"]:checked').map(el=>el.value);
    data['equipment[]'] = $$('input[name="equipment[]"]:checked').map(el=>el.value);
    localStorage.setItem(STORE_KEY, JSON.stringify(data));
  }
  function loadDraft(){
    const raw = localStorage.getItem(STORE_KEY);
    if (!raw) return;
    try{
      const data = JSON.parse(raw);
      Object.entries(data).forEach(([k,v]) => {
        const els = $$(`[name="${k}"]`);
        if (!els.length) return;
        if (Array.isArray(v)){
          els.forEach(el => el.checked = v.includes(el.value));
        } else {
          const el = els[0];
          if (el.type === 'radio' || el.type === 'checkbox'){
            els.forEach(r => r.checked = (r.value == v));
          } else {
            el.value = v;
          }
        }
      });
    }catch(e){}
  }

  // ---------- FORM SUBMIT
  form.addEventListener('submit', (e) => {
    // Validate visible asks in current step and the agreement
    if (!validateCurrentStep()){
      e.preventDefault(); return;
    }
    const agree = $('#agree');
    if (agree && !agree.checked){
      e.preventDefault();
      agree.classList.add('is-invalid');
      showStep(4);
      return;
    }
    // Allow normal submit; to demo client-side success uncomment below:
    // e.preventDefault(); showSuccess();
  });

  function showSuccess(){
    $$('#step-1,#step-2,#step-3,#step-4,#step-5').forEach(s => s.classList.add('d-none'));
    $('#step-success')?.classList.remove('d-none');
    progressEl.style.width = '100%';
    stepTitle.textContent = 'Done — Request Sent';
    stepHelp.textContent  = 'We’ll notify you once it’s approved.';
    stepCounter.textContent = 'Completed';
    localStorage.removeItem(STORE_KEY);
  }

  // ---------- INIT
  loadDraft();
  // Ensure only the first ask of each step is visible initially (unless draft restored)
  steps.forEach(step => {
    const asks = $$('.ask', step);
    if (!asks.length) return;
    const anyFilled = asks.some(a => $$('input,select,textarea', a).some(ctrl => {
      if (ctrl.type === 'radio' || ctrl.type === 'checkbox') return ctrl.checked;
      return !!ctrl.value;
    }));
    if (!anyFilled){
      asks.forEach((a, idx) => {
        a.classList.toggle('d-none', idx!==0);
        a.setAttribute('aria-hidden', String(idx!==0));
      });
    }
  });

  bindAll();
  showStep(0);
  mockConflictCheck();
})();
