(function ($) {
  'use strict';

  if (typeof $ === 'undefined' || !$.fn || !$.fn.turn) {
    console.warn('Magazine: jQuery or turn.js not available');
    return;
  }

  var BOOK_RATIO = 5 / 7;
  var $book = $('#magazineBook');
  var $stage = $('#magazineStage');
  var $prev = $('#magazinePrev');
  var $next = $('#magazineNext');
  var $slider = $('#magazineSlider');
  var $current = $('#magazinePagerCurrent');
  var $total = $('#magazinePagerTotal');
  var $fs = $('#magazineFullscreen');

  var totalPages = parseInt(window.MAGAZINE_TOTAL, 10) || $book.children().length;
  var isReady = false;

  function calcSize() {
    var stageWidth = $stage.innerWidth() - 32;
    var stageHeight = $stage.innerHeight() - 32;
    var isSingle = window.matchMedia('(max-width: 900px)').matches;

    var pageRatio = isSingle ? BOOK_RATIO : (BOOK_RATIO * 2);
    var maxWidthByHeight = stageHeight * pageRatio;
    var width = Math.min(stageWidth, maxWidthByHeight);
    var height = width / pageRatio;

    if (height > stageHeight) {
      height = stageHeight;
      width = height * pageRatio;
    }

    return {
      width: Math.floor(width),
      height: Math.floor(height),
      display: isSingle ? 'single' : 'double',
    };
  }

  function initTurn() {
    var size = calcSize();

    $book.turn({
      width: size.width,
      height: size.height,
      autoCenter: true,
      gradients: true,
      acceleration: true,
      duration: 800,
      display: size.display,
      when: {
        turned: function (event, page) {
          updatePager(page);
        },
      },
    });

    $book.addClass('is-ready');
    isReady = true;
    updatePager(1);
  }

  function updatePager(page) {
    $current.text(page);
    $slider.val(page);
    $prev.prop('disabled', page <= 1);
    $next.prop('disabled', page >= totalPages);
  }

  function resizeBook() {
    if (!isReady) {
      return;
    }
    var size = calcSize();
    var currentDisplay = $book.turn('display');
    if (currentDisplay !== size.display) {
      $book.turn('display', size.display);
    }
    $book.turn('size', size.width, size.height);
  }

  $prev.on('click', function () {
    if (isReady) {
      $book.turn('previous');
    }
  });

  $next.on('click', function () {
    if (isReady) {
      $book.turn('next');
    }
  });

  $slider.on('input change', function () {
    if (!isReady) {
      return;
    }
    var page = parseInt(this.value, 10);
    if (!isNaN(page) && page !== $book.turn('page')) {
      $book.turn('page', page);
    }
  });

  $(document).on('keydown', function (e) {
    if (!isReady) {
      return;
    }
    if (e.key === 'ArrowLeft') {
      $book.turn('previous');
    } else if (e.key === 'ArrowRight') {
      $book.turn('next');
    } else if (e.key === 'Home') {
      $book.turn('page', 1);
    } else if (e.key === 'End') {
      $book.turn('page', totalPages);
    } else if (e.key === 'Escape' && document.fullscreenElement) {
      document.exitFullscreen();
    }
  });

  $fs.on('click', function () {
    var el = document.documentElement;
    if (!document.fullscreenElement) {
      if (el.requestFullscreen) {
        el.requestFullscreen();
      }
    } else if (document.exitFullscreen) {
      document.exitFullscreen();
    }
  });

  var resizeTimer;
  $(window).on('resize orientationchange', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(resizeBook, 120);
  });

  $(window).on('load', initTurn);

  if (document.readyState === 'complete') {
    initTurn();
  }
})(window.jQuery);
