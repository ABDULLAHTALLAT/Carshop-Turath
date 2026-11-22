// Runs in <head> before first paint so there is no flash of the wrong theme
(function () {
  try {
    var saved = localStorage.getItem('tc-theme');
    var dark = saved ? saved === 'dark' : window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (dark) document.documentElement.classList.add('dark');
  } catch (e) {}
})();
