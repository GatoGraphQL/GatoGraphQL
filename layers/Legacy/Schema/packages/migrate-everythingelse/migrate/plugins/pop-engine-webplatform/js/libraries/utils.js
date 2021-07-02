"use strict";
// Taken from https://stackoverflow.com/questions/6941533/get-protocol-domain-and-port-from-url
function getDomain(url) {

    var arr = url.split("/");
    return arr[0] + "//" + arr[2];
}
// function getDomain(url, add_scheme) {

//     var arr = url.split("/");
//     return (add_scheme ? arr[0] + "//" : "") + arr[2];
// }
function removeScheme(domain) {

    var arr = domain.split("//");
    return arr.length == 1 ? arr[0] : arr[1];
}
function getDomainId(domain) {

    // The domain ID is simply removing the scheme, and replacing all dots with '-'
    // It is needed to assign an extra class to the event
    return removeScheme(domain).replace(/\./g, '-');
}

function unescapeHtml(unsafe) {
    // Taken from http://stackoverflow.com/questions/4480757/how-do-i-unescape-html-entities-in-js-change-lt-to
    var d = document.createElement("div");
    d.innerHTML = unsafe;
    return d.innerText || d.text || d.textContent;
}
function add_query_arg(key, value, url){

    url += (url.split('?')[1] ? '&':'?') + key + '=' + value;
    return url;
}
// Copied from https://developers.google.com/web/showcase/2015/service-workers-iowa
function removeQueryFields(url, params) {

  if (!params || !params.length) {
    return url;
  }
  
  // Taken from https://stackoverflow.com/questions/12023430/regex-url-path-from-url
  var parser = document.createElement('a');
  parser.href = url; // This will either produce "/about/" or "/en/about/"

  // Copied from https://developers.google.com/web/showcase/2015/service-workers-iowa
  var search = parser.search.slice(1)
    .split('&')
    .map(function(kv) {
      return kv.split('=');
    })
    .filter(function(kv) {
      return params.every(function(param) {
        return param != kv[0];
      });
    })
    .map(function(kv) {
      return kv.join('=');
    })
    .join('&');

  return parser.protocol+'//'+parser.host+parser.pathname+(search?'?'+search:'')+parser.hash;
}
function removeParams(url) {
    
    if (url.indexOf('?') > -1) {

        return url.substr(0, url.indexOf('?'));
    }
    return url;
}
function getParams(url) {
    
    if (url.indexOf('?') > -1) {

        var params = url.substr(url.indexOf('?')+1);
        
        // Remove the hashmark
        if (params.indexOf('#') > -1) {
            params = params.substr(0, params.indexOf('#'));
        }
        return params;
    }

    return '';
}

// Code taken from here: http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values
var urlParams = {};
function getParam(key, url) {

	var params;
    if (url) {
        // Extract the params bit from the URL
        params = getParams(url);
    }
    else {
        // Default value: URL params
        params = window.location.search.substring(1);
    }
    var paramsObj = splitUrlParams(params);
	return paramsObj[key];
}
function splitUrlParams(params) {

    if (typeof urlParams[params] != 'undefined') {
    
        return urlParams[params];
    }

    urlParams[params] = jQuery.extend({}, splitParams(params));
    return urlParams[params];
}

function splitParams(params) {

    var splitParams = {};
    var e,
        d = function (s) { return decodeURIComponent(s).replace(/\+/g, " "); },
        // Change PoP: must decode the URI
        //q = window.location.search.substring(1),
        q = decodeURI(params),
        r = /([^&=]+)=?([^&]*)/g;

    while (e = r.exec(q)) {
        if (e[1].indexOf("[") == "-1")
            splitParams[d(e[1])] = d(e[2]);
        else {
            var b1 = e[1].indexOf("["),
                aN = e[1].slice(b1+1, e[1].indexOf("]", b1)),
                pN = d(e[1].slice(0, b1));

            if (typeof splitParams[pN] != "object") {
                splitParams[d(pN)] = {};

                // Comment Leo 03/11/2015: commented the line below, because it made the object
                // always keep .length = 0 even after adding elems. It failed in this case:
                // getParam('lid', 'https://www.mesym.com/locations-map/?lid[0]=17501&lid[1]=13540&lid[2]=17504')
                // splitParams[d(pN)].length = 0;
            }

            if (aN)
                splitParams[d(pN)][d(aN)] = d(e[2]);
            else
                Array.prototype.push.call(splitParams[d(pN)], d(e[2]));
        }
    }
    
    // Change PoP: convert all values to string
    return splitParams;
}

function searchInObject(obj, value) {

  // Iterate the object values, check if any of them is the value we are comparing to
  // Taken from https://stackoverflow.com/questions/7306669/how-to-get-all-properties-values-of-a-javascript-object-without-knowing-the-key
  for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
          if (obj[key] == value) {
            return true;
          }
      }
  }
  return false;
};

// Returns a random integer between min and max
// Using Math.round() will give you a non-uniform distribution!
function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min);
}

function doScroll(elem, animate) {

    animate = animate || false;
    var top = elem.offset().top - 20;

    if (animate) {
        jQuery('html, body').animate({ scrollTop: top /* - 20*/ });
    }
    else {
        window.scrollTo(0, top);
    }
}

function removeMarker(url) {

    // If the url has a marker, eg: https://www.mesym.com/announcements/community-composting-group/#block-comments,
    // remove it (since this URL is actually the same without the URL without the marker, or with other markers, so don't load the page again for these cases)
    // This is needed because of bug with Chrome:
    // If loading page http://m3l.localhost/announcements/community-composting-group/, then we type in in the browser
    // http://m3l.localhost/announcements/community-composting-group/#block-addcomment, and then once again
    // http://m3l.localhost/announcements/community-composting-group/#comments, then this last one will execute popState
    // And everything fails since it sends a fetchMainPageSection and it brings back pure HTML
    if (url.indexOf('#') > -1) {

        return url.substr(0, url.indexOf('#'));
    }

    return url;
}

var counter = 0;
function counterNext() {
    counter++;
    return counter;
}

function windowResize() {

    // Dispatch a window resize so that the Calendar / Google map gets updated
    // Taken from http://stackoverflow.com/questions/7621803/how-to-fire-window-onresize-event
    var evt = document.createEvent('UIEvents');
    evt.initUIEvent('resize', true, false, window, 0);
    window.dispatchEvent(evt);

    // Comment Leo 22/02/2017: Waypoints seems to already process this event already
    // Allow others to also react. Eg: waypoints
    // jQuery(document).ready( function($) {
    //     $(window).triggerHandler('resized');
    // });
}

// Taken from https://stackoverflow.com/questions/2723140/validating-url-with-jquery-without-the-validate-plugin
function isUrlValid(url) {
    return /^(https?):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

// Taken from https://stackoverflow.com/questions/879152/how-do-i-make-javascript-beep
function beep() {
    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");  
    snd.play();
}

// Taken from https://stackoverflow.com/questions/11214404/how-to-detect-if-browser-supports-html5-local-storage#11214467
function supports_html5_storage() {

    // Comment Leo 15/08/2017: Modernizr.localstorage doesn't work when the storage is full
    // Then, we won't be able to use the localstorage at all! That was the case for GetPoP
    // return Modernizr.localstorage;
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch(e) {
        return false;
    }
}

// Taken from https://stackoverflow.com/questions/574944/how-to-load-up-css-files-using-javascript#577002
function load_style(path, callback) {

    var head  = document.getElementsByTagName('head')[0];
    var link  = document.createElement('link');
    link.rel  = 'stylesheet';
    link.type = 'text/css';
    link.href = path;
    link.media = 'all';
    head.appendChild(link);

    // There is no bullet proof way to check if the style sheet is loaded, so then just call the callback immediately
    if (callback) {
        callback(path, "ok");
    }
}

// Taken from https://stackoverflow.com/questions/1293367/how-to-detect-if-javascript-files-are-loaded#1293389
function load_script(path, callback, ordered) {

    var done = false;
    var head  = document.getElementsByTagName('head')[0];
    var scr = document.createElement('script');

    // ordered => execute the scripts in order. Needed to load jquery.fileupload-ui before jquery.fileupload-process, or it produces a JS error
    if (ordered) {
        scr.async = false;
    }
    scr.onload = handleLoad;
    scr.onreadystatechange = handleReadyStateChange;
    scr.onerror = handleError;
    scr.src = path;
    // document.body.appendChild(scr);
    head.appendChild(scr);

    function handleLoad() {
        if (!done) {
            done = true;
            if (callback) {
                callback(path, "ok");
            }
        }
    }

    function handleReadyStateChange() {
        var state;

        if (!done) {
            state = scr.readyState;
            if (state === "complete") {
                handleLoad();
            }
        }
    }
    function handleError() {
        if (!done) {
            done = true;
            if (callback) {
                callback(path, "error");
            }
        }
    }
}