(function () {
  'use strict';

  function getIconName(el) {
    return Array.from(el.classList).find(function (c) {
      return c.startsWith('icon-') && c.length > 5;
    });
  }

  function mountLucideIcons(root) {
    if (typeof lucide === 'undefined' || !lucide.createIcons || !lucide.icons) {
      return;
    }

    var scope = root || document;
    var elements = scope.querySelectorAll('.lc-lucide');

    elements.forEach(function (el) {
      if (el.querySelector('svg')) {
        return;
      }

      var iconClass = getIconName(el);
      if (iconClass) {
        el.setAttribute('data-lucide', iconClass.slice(5));
      }
    });

    lucide.createIcons({ icons: lucide.icons, root: scope });
  }

  window.mountLucideIcons = mountLucideIcons;

  document.addEventListener('DOMContentLoaded', function () {
    mountLucideIcons(document);
  });
})();
