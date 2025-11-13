// resources/js/wizard.js

document.addEventListener('DOMContentLoaded', () => {
  const startButton = document.getElementById('wizardStartButton');
  const greetingPanel = document.querySelector('.wizard-greeting-panel');
  const methodSection = document.getElementById('wizardMethodSection');
  const shouldAutoPrompt = methodSection?.dataset.autoStart === '1';

  const clearStartParam = () => {
    const url = new URL(window.location.href);
    if (url.searchParams.has('start')) {
      url.searchParams.delete('start');
      window.history.replaceState({}, '', url);
    }
  };

  const showMethodSelection = () => {
    if (!methodSection) return;

    if (greetingPanel) greetingPanel.classList.add('d-none');
    methodSection.classList.remove('d-none');

    const heading = methodSection.querySelector('.wizard-method-title');
    heading?.focus?.();

    clearStartParam();
  };

  if (startButton) {
    startButton.addEventListener('click', () => {
      const isAuth = startButton.dataset.auth === '1';

      if (!isAuth) {
        window.location.href = '/login';
        return;
      }

      showMethodSelection();
    });
  }

  if (shouldAutoPrompt) {
    showMethodSelection();
  }

  // Handle method choice buttons (Smart vs Manual)
  document.querySelectorAll('.wizard-method-card').forEach(card => {
    card.addEventListener('click', () => {
      const method = card.dataset.method;

      // TODO: branch into actual flows:
      //  - "smart": proceed to question-based Smart Book Finder
      //  - "manual": show Browse All Rooms step (room list + filters)
      //
      // For now we just log the choice so you can verify it works.
      // eslint-disable-next-line no-console
      console.debug('Selected booking method:', method);
    });
  });
});
