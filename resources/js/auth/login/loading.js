document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('lottieAnimation');
  if (container && window.lottie) {
    const animationPath = document.body.dataset.animationPath;
    window.lottie.loadAnimation({
      container,
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: animationPath,
    });
  }

  const params = new URLSearchParams(window.location.search);
  const redirectUrl = params.get('redirect') || document.body.dataset.defaultRedirect;

  setTimeout(() => {
    if (redirectUrl) {
      window.location.href = redirectUrl;
    }
  }, 3000);
});
