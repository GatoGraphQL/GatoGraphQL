
function getParams(url) {
  
  // Function taken from https://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-get-parameters
  if (url.indexOf('?') === -1) return {};

  var query_string = {};
  var query = url.substr(url.indexOf('?')+1);
  // stuff after # is not part of query string, so get rid of it
  query = query.split('#')[0];

  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
        // If first entry with this name
    if (typeof query_string[pair[0]] === "undefined") {
      query_string[pair[0]] = decodeURIComponent(pair[1]);
        // If second entry with this name
    } else if (typeof query_string[pair[0]] === "string") {
      var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
      query_string[pair[0]] = arr;
        // If third or later entry with this name
    } else {
      query_string[pair[0]].push(decodeURIComponent(pair[1]));
    }
  } 
  return query_string;
}

function stripIgnoredUrlParameters(originalUrl, ignoredParams) {

  if (!ignoredParams || !ignoredParams.length) return originalUrl;
  
  // Copied from https://developers.google.com/web/showcase/2015/service-workers-iowa
  var url = new URL(originalUrl);

  url.search = url.search.slice(1)
    .split('&')
    .map(function(kv) {
      return kv.split('=');
    })
    .filter(function(kv) {
      return ignoredParams.every(function(ignoredParam) {
        return ignoredParam != kv[0];
      });
    })
    .map(function(kv) {
      return kv.join('=');
    })
    .join('&');

  return url.toString();
}
