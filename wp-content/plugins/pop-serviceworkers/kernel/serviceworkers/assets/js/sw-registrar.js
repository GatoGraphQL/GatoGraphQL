(function (global) {

  if (global.$swRegistrations) {
    return;
  }

  global.$swRegistrations = {};

  if ('serviceWorker' in navigator) {
    var enabledSw = $enabledSw;
    enabledSw.forEach(function(entry) {
      var scope = entry.scope;
      var swUrl = entry.url;
      global.$swRegistrations[scope] = navigator.serviceWorker.register(swUrl, { scope: scope });
    });
  }

})(window);
