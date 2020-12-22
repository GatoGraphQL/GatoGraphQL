(function (global) {

  if (global.$swRegistrations) {
    return;
  }

  global.$swRegistrations = {};

  if ('serviceWorker' in navigator) {
    // Wait until the page is loaded, to register the SW, to improve user experience (source: https://developers.google.com/web/fundamentals/primers/service-workers/registration)
    window.addEventListener('load', function() {

      if (!pop.c.USE_SW) {
        return;
      }
      var enabledSw = $enabledSw;
      enabledSw.forEach(function(entry) {
        var scope = entry.scope;
        var swUrl = entry.url;
        global.$swRegistrations[scope] = navigator.serviceWorker.register(swUrl, { scope: scope });
      });
    });
  }

})(window);
