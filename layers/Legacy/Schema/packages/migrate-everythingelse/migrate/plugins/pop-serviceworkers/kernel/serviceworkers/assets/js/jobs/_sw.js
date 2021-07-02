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
    appshell: {
        pages: $appshellPages,
        params: {
            precached: $appshellPrecachedParams,
            fromserver: $appshellFromServerParams
        }
    },
    locales: {
        all: $localesByURL,
        default: $defaultLocale,
            current: null,
            domain: null
            },
            themes: $themes,
            outputJSON: $outputJSON,
            origins: $origins,
            multidomains: $multidomains,
            strategies: $strategies,
            ignore: $ignore,
            params: {
                cachebust: $cacheBustParam
            },
            contentCDN: {
                params: $contentCDNParams,
                domains: {
                    cdn: $contentCDNDomain,
                    original: $contentCDNOriginalDomain
                }
            },
            previousRequestURLs: {}
            };

            function cacheName(key, opts)
            {
                    return `${opts.version}-${key}`;
            }

    /**
     * This function returns the Original URL from which the Content CDN URL originated
     * Eg:
     * Final (request) url: https://content.getpop.org/en/posts/?v=0.455&tp=893932274398
     * Alias (original) url: https://getpop.org/en/posts/
     */
            function getOriginalURL(url, opts)
            {

                    // If the current URL is pointing to the Content CDN
                if (opts.contentCDN.domains.cdn && url.substr(0, opts.contentCDN.domains.cdn.length) == opts.contentCDN.domains.cdn) {
        // Replace the domain, from the CDN one to the original one
                    url = opts.contentCDN.domains.original + url.substr(opts.contentCDN.domains.cdn.length);
  
        // Remove the unneeded parameters, eg: "v" (version), "tp" (thumbprint)
                    url = stripIgnoredUrlParameters(url, opts.contentCDN.params);
                }

                    return url;
            }

            function addToCache(cacheKey, request, response, opts)
            {
                    // If coming from function refresh, response might be null
                    // Comment Leo 06/03/2017: calling addToCache before refresh now, so no need to ask if response is not null
                    // if (response !== null && response.ok) {
                if (response.ok) {
        // Add to the cache
                    var copy = response.clone();
                    caches.open(cacheKey).then(cache => {
                              cache.put(request, copy);
                    });

        // Save an entry on IndexedDB for the alias URL to point to this request
                    var original = getOriginalURL(request.url, opts);
                    if (original != request.url) {
                          // First save the previous alias where the info was stored, so we can also send a message later on, on function 'refresh'
                          localforage.getItem('Alias-'+original).then(previousRequestURL => {

                  // Add the previous URL in the opts, for use in function `refresh`
                                if (previousRequestURL && previousRequestURL != request.url) {
                                    opts.previousRequestURLs[request.url] = previousRequestURL;
                                }

                  // Set the new request on that position
                                if (previousRequestURL != request.url) {
                                    localforage.setItem('Alias-'+original, request.url);
                                }
                                  });
                    }
                }
                    return response;
            }

            self.addEventListener('install', event => {
                function onInstall(event, opts)
                {
    
                    var resourceTypes = ['static', 'json', 'html'];
        // Taken from https://brandonrozek.com/2015/11/service-workers/
                    return Promise.all(resourceTypes.map(function (resourceType) {
                              return caches.open(cacheName(resourceType, opts)).then(function (cache) {
                                return Promise.all(opts.cacheItems[resourceType].map(function (url) {
                                    return fetch(url, (new URL(url)).origin === self.location.origin ? {} : {mode: 'no-cors'}).then(function (response) {
                                        return cache.put(url, response.clone());
                                    });
                                }))
                              })
                    }))
                }

                    event.waitUntil(
                        onInstall(event, config).then(() => self.skipWaiting())
                    );
            });

            self.addEventListener('activate', event => {
                function onActivate(event, opts)
                {
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
                          .then(() => self.clients.claim())
                      );
            });

            function fetchFromCache(request)
            {
                        return caches.match(request).then(response => {
                            if (!response) {
                                throw Error(`${request.url} not found in cache`);
                            }
                            return response;
                                });
            }

            self.addEventListener('fetch', event => {

                function shouldHandleFetch(event, opts)
                {
    
                    var request = event.request;
                    var resourceType = getResourceType(request);
                    var url = new URL(request.url);
                    var criteria = {
                        isNotExcluded: !opts.excludedPaths.full[resourceType].some(path => request.url.startsWith(path)),
                              // The pages do not include the locale domain, or any of the multidomains (adding the current locale), or the multidomains for the default locale (eg: GetPoP has ES language, but MESYM does not, so it will fallback to its EN default lang) so add it before doing the comparison
                        isPageNotExcluded: !opts.excludedPaths.partial[resourceType].some(path => request.url.startsWith(opts.locales.domain+path)),
                        isNotExternalDomain: opts.multidomains.indexOf(url.origin) == -1,
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

                function getCurrentDomain(event, opts)
                {
    
                    return Object.keys(opts.locales.all).filter(path => event.request.url.startsWith(path));
                }

                function getLocaleDomain(event, opts)
                {
    
                    var currentDomain = getCurrentDomain(event, opts);
                    if (currentDomain.length) {
                        return currentDomain[0];
                    }

                    // Return the default domain
                    return Object.keys(opts.locales.all).filter(function (key) {
                        return opts.locales.all[key] === opts.locales.default})[0];
                }

                function getLocale(event, opts)
                {
    
                    var currentDomain = getCurrentDomain(event, opts);
                    if (currentDomain.length) {
                        return opts.locales.all[currentDomain];
                    }
                    return opts.locales.default;
                }

                function initOpts(opts, event)
                {
    
                    // Find the current locale and set it on the configuration object
                    opts.locales.current = getLocale(event, opts);
                    opts.locales.domain = getLocaleDomain(event, opts);
                    return opts;
                }

                function getResourceType(request)
                {
    
                    var acceptHeader = request.headers.get('Accept');

                    var resourceType = 'static';

                    // Make sure that it is not a static resource that is being requested
                    var resourceExtensions = ['txt', 'ico', 'xml', 'xsl', 'css', 'js', 'eot', 'svg', 'ttf', 'woff', 'woff2', 'otf', 'jpg', 'jpeg', 'png', 'gif', 'pdf'];
                    if (acceptHeader.indexOf('text/html') !== -1 && !resourceExtensions.some(extension => request.url.endsWith('.'+extension) || request.url.indexOf('.'+extension+'?') > -1)) {
                        resourceType = 'html';
                    } else if (acceptHeader.indexOf('application/json') !== -1) {
                        resourceType = 'json';
                    }

                    return resourceType;
                }

                function getRequest(request, opts)
                {

                    var resourceType = getResourceType(request);

                    // The 'html' and 'static' behave in the same way, with the difference that 'html' must always
                    // point to the appshell page. If this one is not cached (eg: the user deleted it manually using Dev Tools)
                    // then can still fetch it (the appshell page, not the requested page) and cache it
                    if (resourceType === 'html') {
                      // For HTML use a different URL: the appshell page

                      // The different appshells are a combination of locale, theme and thememode
                        var params = getParams(request.url);
                        var theme = params[opts.appshell.params.precached.theme] || opts.themes.default;
                        var thememode = params[opts.appshell.params.precached.thememode] || (opts.themes.themes[theme] ? opts.themes.themes[theme].default : '');

                      // The initial appshell URL has the params that we have precached
                        var url = opts.appshell.pages[opts.locales.current][theme][thememode];

                      // In addition, there are other params that, if provided by the user, they must be added to the URL
                      // These params are not originally precached in any appshell URL, so such page will have to be retrieved from the server
                        opts.appshell.params.fromserver.forEach(function (param) {

                          // If the param was passed in the URL, then add it along
                            if (params[param]) {
                                url += '&'+param+'='+params[param];
                            }
                        });
                        request = new Request(url);
                    } else if (resourceType === 'json') {
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

                function getCacheBustRequest(request, opts)
                {

                    var url = new URL(request.url);

                    // Put in a cache-busting parameter to ensure we're caching a fresh response.
                    if (url.search) {
                        url.search += '&';
                    }
    
                    // Comment Leo 14/08/2017: We set the cache-busting number to change every 1 second
                    // This is done because the request will go through the CDN. So if many users, at the
                    // same second, access the same page in the website, the SW refresh will get the response
                    // from the CDN, and it won't reach the server.
                    // var fresh = Date.now(); // Every millisecond
                    var fresh = Math.floor(Date.now()/1000); // Every second
                    url.search += opts.params.cachebust + '=' + fresh;

                    return new Request(url.toString());
                }

                function getStrategy(request, opts)
                {
    
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
                        } else {
                            strategy = SW_STRATEGIES_CACHEFIRSTTHENREFRESH;
                        }
                    } else if (resourceType === 'html' || resourceType === 'static') {
                        strategy = SW_STRATEGIES_CACHEFIRST;
                    }

                    return strategy;
                }

                function refresh(request, response, opts)
                {

                    var ETag = response.headers.get('ETag');
                    if (!ETag) {
                        return null;
                    }
    
                    // Comment Leo 04/04/2017: use the original URL instead of the response.url, so that 2 responses with different thumbprints
                    // are considered the same URL. Otherwise, it won't find the other one, and won't show the refresh message
                    // var key = response.url;
                    var key = 'ETag-'+getOriginalURL(request.url, opts);
                    return localforage.getItem(key).then(function (previousETag) {

                      // Compare the ETag of the response, with the previous one, saved in the IndexedDB
                        if (ETag == previousETag) {
                            return null;
                        }

                      // Save the new value
                        return localforage.setItem(key, ETag).then(function () {

                          // If there was no previous ETag, then send no notification to the user
                            if (!previousETag) {
                                return null;
                            }

                          // Send a message to the client
                            return self.clients.matchAll().then(function (clients) {
                                clients.forEach(function (client) {
                                    var message = {
                                        type: 'refresh',
                          // Comment Leo 16/03/2017: Use the request.url for the key, instead of the response.url, because sometimes these 2 are different.
                        // Eg: https://getpop.org for request, https://getpop.org/en/ for response
                        // When this happens, the notification to the user to refresh the page doesn't appear because it was generated using the request url
                        // url: response.url
                        // // Comment Leo 04/04/2017: after adding the PoP CDN, the request URL will have the thumbprint param
                        // // However, if the page was open immediately using the cached version, and then there is a newer version
                        // // after being fetched with a different thumbprint, then it wouldn't find that tab
                        // // So then use original URL so it can always be found
                        // url: getOriginalURL(request.url, opts)
                                        url: request.url
                                    };
                                    client.postMessage(JSON.stringify(message));

                                // If there was a previousRequestURL, also send to that one
                                    if (opts.previousRequestURLs[request.url]) {
                                        message.url = opts.previousRequestURLs[request.url];
                                        client.postMessage(JSON.stringify(message));
                                        delete opts.previousRequestURLs[request.url];
                                    }
                                });
                                return response;
                            });
                        });
                    });
                }

                function onFetch(event, opts)
                {

                    var request = event.request;
                    var resourceType = getResourceType(request);
                    var cacheKey = cacheName(resourceType, opts);

                    // Important: obtain the strategy first, and only then modify the request. That way we can force a given
                    // strategy by a parameter in the URL, but remove it before sending it to the server
                    var strategy = getStrategy(request, opts);

                    // Allow to modify the request, fetching content from a different URL
                    request = getRequest(request, opts);
                    var fetchOpts = {};
                    // var origin = (new URL(request.url)).origin;
                    // if (opts.origins.indexOf(origin) > -1) {
                    //   fetchOpts.mode = 'cors';
                    // }

                    // Add the cache buster param for JSON responses, no need for static assets or for the initial appshell load (if this one changes, then the version will change and get downloaded and cached again)
                    var cacheBustRequest = getCacheBustRequest(request, opts);

                    if (strategy === SW_STRATEGIES_CACHEFIRST || strategy === SW_STRATEGIES_CACHEFIRSTTHENREFRESH) {
                      // Bust the cache. Taken from https://developers.google.com/web/showcase/2015/service-workers-iowa

                      /* Load immediately from the Cache */
                        event.respondWith(
                            fetchFromCache(request)
                            // Check if an alternate version of the same URL has cached this result
                            // Needed for integrating SW with the content CDN (pop-cdn)
                            // Eg: if there is no content under https://content.getpop.org/en/loaders/posts/layouts/?...&v=0.358&tp=1487686590
                            // maybe there is under a previous version, like https://content.getpop.org/en/loaders/posts/layouts/?...&v=0.358&tp=1487683333
                            // then use that one
                            // We obtain the URL for this alternate request under the Alias URL in IndexedDB
                            .catch(() => localforage.getItem('Alias-'+getOriginalURL(request.url, opts)).then(alternateRequestURL => fetchFromCache(new Request(alternateRequestURL))))
                            .catch(() => fetch(request, fetchOpts))
                            .then(response => addToCache(cacheKey, request, response, opts))
                        );

                      /* Bring fresh content from the server, and show a message to the user if the cached content is stale */
                      /* Only do it for the json resourceType, since html will never change (unless $version is updated),
                      and static will also not change (.css and .js will also have their version changed, and images
                      never get updated under the same filename, the Media Manager will upload them with a different filename the 2nd time) */
                        if (strategy === SW_STRATEGIES_CACHEFIRSTTHENREFRESH) {
                            event.waitUntil(
                                fetch(cacheBustRequest, fetchOpts)
                                // Comment Leo 06/03/2017: 1st save the cache back (even without checking the ETag), and only then call refresh,
                                // because somehow sometimes the response ETag different than the stored one, it was saved, but nevertheless the
                                // SW cache returned the previous content!
                                .then(response => addToCache(cacheKey, request, response, opts))
                                .then(response => refresh(request, response, opts))
                            );
                        }
                    } else if (strategy === SW_STRATEGIES_NETWORKFIRST) {
                        event.respondWith(
                            fetch(cacheBustRequest, fetchOpts)
                            .then(response => addToCache(cacheKey, request, response, opts))
                            .catch(() => fetchFromCache(request))
                            .catch(function (err) {
                            /*console.log(err)*/})
                        );
                    }
                }
                  config = initOpts(config, event);
                if (shouldHandleFetch(event, config)) {
                    onFetch(event, config);
                }

            });
