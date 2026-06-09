(function () {
  'use strict';

  function sameOriginReferrer() {
    if (!document.referrer) {
      return false;
    }

    try {
      return new URL(document.referrer).origin === window.location.origin;
    } catch (error) {
      return false;
    }
  }

  function goBack(fallbackUrl) {
    if (window.history.length > 1 && sameOriginReferrer()) {
      window.history.back();
      return;
    }

    window.location.href = fallbackUrl;
  }

  document.querySelectorAll('.js-report-viewer-back').forEach(function (button) {
    button.addEventListener('click', function () {
      goBack(button.getAttribute('data-fallback') || '/reports');
    });
  });
})();
