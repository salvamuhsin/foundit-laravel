// FoundIt: theme toggle and account menu. Collapse/offcanvas come from Bootstrap's own JS.
(function () {
  var root = document.documentElement;

  function paintThemeIcon() {
    var dark = root.getAttribute('data-bs-theme') === 'dark';
    document.querySelectorAll('[data-theme-toggle] i').forEach(function (i) {
      i.className = 'bi ' + (dark ? 'bi-sun' : 'bi-moon-stars');
    });
  }

  function setTheme(t) {
    root.setAttribute('data-bs-theme', t);
    try { localStorage.setItem('foundit:theme', JSON.stringify(t)); } catch (e) {}
    paintThemeIcon();
  }

  function closeMenus(except) {
    document.querySelectorAll('.account-wrap').forEach(function (w) {
      if (w === except) return;
      var menu = w.querySelector('.account-menu');
      var btn = w.querySelector('[data-account-toggle]');
      if (menu) menu.setAttribute('hidden', '');
      if (btn) btn.setAttribute('aria-expanded', 'false');
    });
  }

  document.addEventListener('click', function (e) {
    if (e.target.closest('[data-theme-toggle]')) {
      setTheme(root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
      return;
    }
    var toggle = e.target.closest('[data-account-toggle]');
    if (toggle) {
      var wrap = toggle.closest('.account-wrap');
      var menu = wrap.querySelector('.account-menu');
      var willOpen = menu.hasAttribute('hidden');
      closeMenus(wrap);
      menu.toggleAttribute('hidden', !willOpen);
      toggle.setAttribute('aria-expanded', String(willOpen));
      return;
    }
    if (!e.target.closest('.account-wrap')) closeMenus(null);
  });

  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenus(null); });
  document.addEventListener('DOMContentLoaded', paintThemeIcon);
})();
