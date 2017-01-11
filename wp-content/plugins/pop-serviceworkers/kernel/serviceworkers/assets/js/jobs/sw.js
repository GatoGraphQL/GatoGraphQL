/* global self, caches, fetch, URL, Response */
'use strict';

importScripts('./$dependenciesFolder/localforage.1.4.3.min.js');
importScripts('./$dependenciesFolder/utils.js');

const SW_STRATEGIES_CACHEFIRST = 1;
const SW_STRATEGIES_CACHEFIRSTTHENREFRESH = 2;
const SW_STRATEGIES_NETWORKFIRST = 3;

var config = {
  version: $version,
  cacheItems: $cacheItems,
  excludedPaths: {
    full: $excludedFullPaths,
    partial: $excludedPartialPaths
  },
  appshellPages: $appshellPages,
  locales: {
    all: $localesByURL,
    default: $defaultLocale,
    current: null,
    domain: null
  },
  themes: $themes,
  outputJSON: $outputJSON,
  origins: $origins,
  strategies: $strategies,
  ignore: $ignore,
};

function cacheName(key, opts) {
  return `${opts.version}-${key}`;
}

function addToCache(cacheKey, request, response) {
  // If coming from function refresh, response might be null
  if (response !== null && response.ok) {
    var copy = response.clone();
    caches.open(cacheKey).then( cache => {
      cache.put(request, copy);
    });
  }
  return response;
}

self.addEventListener('install', event => {
  function onInstall(event, opts) {
    
    var resourceTypes = ['static', 'json', 'html'];
    // Taken from https://brandonrozek.com/2015/11/service-workers/
    return Promise.all(resourceTypes.map(function(resourceType) {
      return caches.open(cacheName(resourceType, opts)).then(function(cache) {
        return Promise.all(opts.cacheItems[resourceType].map(function(url) {
          return fetch(url, (new URL(url)).origin === self.location.origin ? {} : {mode: 'no-cors'}).then(function(response) {
            return cache.put(url, response.clone());
          });
        }))
      })
    }))    
  }

  event.waitUntil(
    onInstall(event, config).then( () => self.skipWaiting() )
  );
});

self.addEventListener('activate', event => {
  function onActivate(event, opts) {
    var p1 = localforage.clear(); // Delete all ETag entries in the DB
    var p2 = caches.keys()
      .then(cacheKeys => {
        var oldCacheKeys = cacheKeys.filter(key => !key.startsWith(opts.version));
        var deletePromises = oldCacheKeys.map(oldKey => caches.delete(oldKey));
        return Promise.all(deletePromises);
      });
    return Promise.all([p1, p2]);
  }

  event.waitUntil(
    onActivate(event, config)
      .then( () => self.clients.claim() )
  );
});

function fetchFromCache(request) {
  return caches.match(request).then(response => {
    if (!response) {
      throw Error(`${request.url} not found in cache`);
    }
    return response;
  });
}

self.addEventListener('fetch', event => {

  function shouldHandleFetch(event, opts) {
    
    var request = event.request;
    var resourceType = getResourceType(request);
    var url = new URL(request.url);
    var criteria = {
      isNotExcluded: !opts.excludedPaths.full[resourceType].some(path => request.url.startsWith(path)),
      // The pages do not included the locale domain, so add it before doing the comparison
      isPageNotExcluded: !opts.excludedPaths.partial[resourceType].some(path => request.url.startsWith(opts.locales.domain+path)),
      isGETRequest: request.method === 'GET',
      // Either the resource comes from my origin(s) (eg: including my personal CDN), or it has been precached (eg: from an external cdn, such as cdnjs.cloudflare.com)
      isFromMyOriginsOrPrecached: (opts.origins.indexOf(url.origin) > -1 || opts.cacheItems[resourceType].indexOf(url) > -1)
    };

    var failingCriteria = Object.keys(criteria).filter(criteriaKey => !criteria[criteriaKey]);
    if (failingCriteria.length) {
      return false;
    }

    // resourceType-specific criteria
    var resourceTypeCriteria = {
      'html': {
        // For 'html' case: make sure it doesn't have output=JSON, because in that case, we're trying to see the JSON on the browser, then no need to use the appshell
        isNotInitialJSON: request.url.indexOf(opts.outputJSON) === -1
      },
      'json': {},
      'static': {
        // Do not handla dynamic images, eg: the Captcha: wp-content/plugins/pop-coreprocessors/library/captcha/captcha.png.php
        isNotDynamic: !request.url.endsWith('.php') && request.url.indexOf('.php?') === -1
      }
    };

    criteria = resourceTypeCriteria[resourceType];
    failingCriteria = Object.keys(criteria).filter(criteriaKey => !criteria[criteriaKey]);
    return !failingCriteria.length;
  }

  function getCurrentDomain(event, opts) {
    
    return Object.keys(opts.locales.all).filter(path => event.request.url.startsWith(path));
  }

  function getLocaleDomain(event, opts) {
    
    var currentDomain = getCurrentDomain(event, opts);
    if (currentDomain.length) {
      return currentDomain[0];
    }

    // Return the default domain
    return Object.keys(opts.locales.all).filter(function(key) {return opts.locales.all[key] === opts.locales.default})[0];
  }

  function getLocale(event, opts) {
    
    var currentDomain = getCurrentDomain(event, opts);
    if (currentDomain.length) {
      return opts.locales.all[currentDomain];
    }
    return opts.locales.default;
  }

  function initOpts(opts, event) {
    
    // Find the current locale and set it on the configuration object
    opts.locales.current = getLocale(event, opts);
    opts.locales.domain = getLocaleDomain(event, opts);
    return opts;
  }

  function getResourceType(request) {
    
    var acceptHeader = request.headers.get('Accept');
    var resourceType = 'static';

    if (acceptHeader.indexOf('text/html') !== -1) {
      resourceType = 'html';
    } 
    else if (acceptHeader.indexOf('application/json') !== -1) {
      resourceType = 'json';
    } 

    return resourceType;
  }

  function getRequest(request, opts) {

    var resourceType = getResourceType(request);

    // The 'html' and 'static' behave in the same way, with the difference that 'html' must always
    // point to the appshell page. If this one is not cached (eg: the user deleted it manually using Dev Tools)
    // then can still fetch it (the appshell page, not the requested page) and cache it
    if (resourceType === 'html') {
      // For HTML use a different URL: the appshell page

      // The different appshells are a combination of locale, theme and thememode
      var params = getParams(request.url);
      var theme = params[opts.themes.params.theme] || opts.themes.default;
      var thememode = params[opts.themes.params.thememode] || opts.themes.themes[theme].default;
      request = new Request(opts.appshellPages[opts.locales.current][theme][thememode]);
    }
    else if (resourceType === 'json') {

      // Remove the ignore strings, we don't want to send it to the server, it was added to the URL
      // only to bypass the Cache First strategy
      // // For convenience, the URL must finish with this string
      // var ignore = opts.ignore[resourceType];
      // ignore.forEach(function(str) {
      //   if (request.url.endsWith(str)) {
      //     request = new Request(request.url.substr(0, request.url.indexOf(str)));
      //   }
      // });
      request = new Request(stripIgnoredUrlParameters(request.url, opts.ignore[resourceType]));
    }
    
    return request;
  }

  function getStrategy(request, opts) {
    
    var strategy = '';
    var resourceType = getResourceType(request);

    // JSON requests have 2 strategies: cache first + update (the default) or network first
    if (resourceType === 'json') {

      var networkFirst = opts.strategies[resourceType].networkFirst;
      var criteria = {
        startsWith: networkFirst.startsWith.full.some(path => request.url.startsWith(path)),
        // The pages do not included the locale domain, so add it before doing the comparison
        pageStartsWith: networkFirst.startsWith.partial.some(path => request.url.startsWith(opts.locales.domain+path)),
        // endsWith: networkFirst.endsWith.some(path => request.url.endsWith(path)),
        hasParams: stripIgnoredUrlParameters(request.url, networkFirst.hasParams) != request.url
      }
      var successCriteria = Object.keys(criteria).filter(criteriaKey => criteria[criteriaKey]);
      if (successCriteria.length) {

        strategy = SW_STRATEGIES_NETWORKFIRST;
      }
      else {

        strategy = SW_STRATEGIES_CACHEFIRSTTHENREFRESH;        
      }
    }
    else if (resourceType === 'html' || resourceType === 'static') {

      strategy = SW_STRATEGIES_CACHEFIRST;
    }

    return strategy;
  }

  function refresh(request, response) {

    var ETag = response.headers.get('ETag');
    if (!ETag) {
      return null;
    }
    
    var key = 'ETag-'+response.url;
    return localforage.getItem(key).then(function(previousETag) {

      // Compare the ETag of the response, with the previous one, saved in the IndexedDB
      if (ETag == previousETag) {
        return null;
      }

      // Save the new value
      return localforage.setItem(key, ETag).then(function() {

        // If there was no previous ETag, then send no notification to the user
        if (!previousETag) {
          return null;
        }

        // Send a message to the client
        return self.clients.matchAll().then(function (clients) {
          clients.forEach(function (client) {
            var message = {
              type: 'refresh',
              url: response.url
            };
       
            client.postMessage(JSON.stringify(message));
          });
          return response;
        });
      });
    });
  }

  function onFetch(event, opts) {

    var request = event.request;
    var resourceType = getResourceType(request);
    var cacheKey = cacheName(resourceType, opts);

    // Important: obtain the strategy first, and only then modify the request. That way we can force a given
    // strategy by a parameter in the URL, but remove it before sending it to the server
    var strategy = getStrategy(request, opts);

    // Allow to modify the request, fetching content from a different URL
    request = getRequest(request, opts);

    if (strategy === SW_STRATEGIES_CACHEFIRST || strategy === SW_STRATEGIES_CACHEFIRSTTHENREFRESH) {
    
      /* Load immediately from the Cache */
      event.respondWith(
        fetchFromCache(request)
          .catch(() => fetch(request))
          .then(response => addToCache(cacheKey, request, response))
      );

      /* Bring fresh content from the server, and show a message to the user if the cached content is stale */
      /* Only do it for the json resourceType, since html will never change (unless $version is updated), 
      and static will also not change (.css and .js will also have their version changed, and images 
      never get updated under the same filename, the Media Manager will upload them with a different filename the 2nd time) */
      if (strategy === SW_STRATEGIES_CACHEFIRSTTHENREFRESH) {
        event.waitUntil(
          fetch(request)
            .then(response => refresh(request, response))
            .then(response => addToCache(cacheKey, request, response))
        );
      }
    }
    else if (strategy === SW_STRATEGIES_NETWORKFIRST) {

      event.respondWith(
        fetch(request)
          .then(response => addToCache(cacheKey, request, response))
          .catch(() => fetchFromCache(request))
          .catch(function(err) {/*console.log(err)*/})
      );
    }
  }
  config = initOpts(config, event);
  if (shouldHandleFetch(event, config)) {

    onFetch(event, config);
  }

});
