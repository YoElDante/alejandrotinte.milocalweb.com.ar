/**
 * Smooth scroll for anchor links — compensates sticky header height
 */
(function () {
  'use strict';
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');
      if (targetId === '#') {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return;
      }
      var target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        var header = document.querySelector('.site-header');
        var offset = header ? header.offsetHeight : 0;
        var position = target.getBoundingClientRect().top + window.scrollY - offset - 16;
        window.scrollTo({ top: position, behavior: 'smooth' });
      }
    });
  });
})();
