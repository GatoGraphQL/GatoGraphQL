// Configuration
var config = require('./config.js');

// All the commented code is because AWS already send the querystring on its own, no need to extract it from the URL
// var getParams = function(url) {
    
//     if (url.indexOf('?') > -1) {

//         var params = url.substr(url.indexOf('?')+1);
        
//         // Remove the hashmark
//         if (params.indexOf('#') > -1) {
//             params = params.substr(0, params.indexOf('#'));
//         }
//         return params;
//     }

//     return '';
// }

var stripIgnoredUrlParameters = function(urlParams/*url*/, ignoredParams) {

  // if (!ignoredParams || !ignoredParams.length) return url;
  if (!ignoredParams || !ignoredParams.length) return urlParams;
  
  // Copied from https://developers.google.com/web/showcase/2015/service-workers-iowa
  // var urlParams = getParams(url);
  if (urlParams) {

    // var newParams = urlParams
    urlParams = urlParams
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

    // url = url.replace(urlParams, newParams);

    // // If all params were stripped, then eliminate the '?' from the end
    // if (url.slice(-1) == '?') {
    //   url = url.substr(0, url.length - 1);
    // }
  }

  // return url;
  return urlParams;
}

exports.handler = function(event, context, callback) {
	
	var request = event.Records[0].cf.request;

	// Remove parameter 'sw-cachebust' from the request, which was generated using Date.now(), 
	// only to avoid the Browser Cache and make sure to hit the network in the service workers
	request.querystring = stripIgnoredUrlParameters(request.querystring, config.params);
	
	callback(null , event.Records[0].cf.request);
}
