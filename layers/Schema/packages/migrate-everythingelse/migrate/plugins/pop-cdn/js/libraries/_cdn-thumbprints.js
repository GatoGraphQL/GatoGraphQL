"use strict";
(function ($) {
    window.popCDNThumbprints = {

        // This variable will be filled through cdn-config.js
        config : {},

        //-------------------------------------------------
        // PRIVATE functions
        //-------------------------------------------------

        getConfig : function (url) {
  
            var that = this;
            var domain = getDomain(url);

            // Just making sure the .js file containing popCDNConfig was generated ok. If not, fail gracefully
            // if (!popCDNConfig) return false;
            if ($.isEmptyObject(that.config) || typeof that.config[domain] == 'undefined') {
                return {};
            }

            return that.config[domain];
        },

        canUseCDN : function (url) {
  
            var that = this;

            // Just making sure the .js file containing popCDNConfig was generated ok. If not, fail gracefully
            // if (!popCDNConfig) return false;
            var config = that.getConfig(url);
            if ($.isEmptyObject(config)) {
                    return false;
            }

            // Can use, if that URL is not rejected
            // return !that.evalCriteria(url, popCDNConfig.criteria.rejected);
            return !that.evalCriteria(url, config.criteria.rejected);
        },

        getThumbprints : function (url) {
  
            var that = this;

            // Just making sure the .js file containing popCDNConfig was generated ok. If not, fail gracefully
            // if (!popCDNConfig) {
            var config = that.getConfig(url);
            if ($.isEmptyObject(config)) {
                    console.log('There is no config object, no thumbprints returned');
                    return [];
            }
    
            var thumbprints = [];
            // $.each(popCDNConfig.thumbprints, function(index, thumbprint) {
            $.each(
                config.thumbprints,
                function (index, thumbprint) {

                    // if (that.evalCriteria(url, popCDNConfig.criteria.thumbprints[thumbprint])) {
                    if (that.evalCriteria(url, config.criteria.thumbprints[thumbprint])) {
                        thumbprints.push(thumbprint);
                    }
                }
            );

            return thumbprints;
        },

        isHome : function (url) {

            var path = removeParams(url);
            var possible = [M.HOME_DOMAIN, M.HOME_DOMAIN+'/', M.HOMELOCALE_URL, M.HOMELOCALE_URL+'/'];
            return possible.indexOf(path) > -1;
        },

        evalCriteria : function (url, entries) {
  
            var that = this;

            var evalParam = function (elem) {

                    var key = elem[0], value = elem[1];
                    var paramValue = getParam(key, url);

                    // The parameter may be a single value, or an array. In the latter case,
                    // compare that the value to check (eg: field 'postcomments') is inside the array (eg: fields[0]=postcomments&fields[1]=postauthor => ['postcomments', 'postauthor'])
                    // In that case, paramValue will be an object, looking like this:
                    // fields:Object
                    //   0:"recommendpost-count"
                    //   1:"recommendpost-count-plus1"
                    //   2:"userpostactivity-count"
                if (typeof paramValue == "object") {
                    // Iterate the object values, check if any of them is the value we are comparing to
                    // Taken from https://stackoverflow.com/questions/7306669/how-to-get-all-properties-values-of-a-javascript-object-without-knowing-the-key
                    return searchInObject(paramValue, value);
                }
                return paramValue == value;
            };

            // All of the criterias in the array below must be successful
            // Each criteria is an object with items; only 1 item must be successful for that criteria to be successful
            // So we are executing something like AND (OR ... OR ... OR ...) AND (OR ... OR ... OR ...) ...
            // This is needed because item 'noParamValues' must always be successful for the whole to be successful,
            // but the others, just 1 of them will do.
            // Eg: for the authors URLs, we have:
            // thumbprint POST with criteriaitem 'startsWith'=>'getpop.org/en/u/'
            // thumbprint USER with criteriaitem 'startsWith'=>'getpop.org/en/u/' and 'noParamValues' => ['tab=followers', 'tab=description']
            // Calling below URLs then produce thumbprints:
            // getpop.org/en/u/leo/ => POST+USER
            // getpop.org/en/u/leo/?tab=articles => POST+USER
            // getpop.org/en/u/leo/?tab=followers => USER
            // getpop.org/en/u/leo/?tab=description => USER
            var criterias = [
            {
                // isHome: special case, we can't ask for path pattern, or otherwise its thumbprints will always be true for everything else (since everything has the path of the home)
                isHome: entries.isHome && that.isHome(url),

                startsWith: entries.startsWith.full.some(
                    function (path) {
          
                        return url.startsWith(path);
                    }
                ),

            // The pages do not included the locale domain, so add it before doing the comparison
            pageStartsWith: entries.startsWith.partial.some(
                function (path) {
          
                    return url.startsWith(M.HOMELOCALE_URL+'/'+path);
                }
            ),

            // Check if the combination of key=>value is present as a param in the URL
            hasParamValues: entries.hasParamValues.some(evalParam)
            },
            {
                    // Check that the combination of key=>value is NOT present as a param in the URL
                noParamValues: !entries.noParamValues.some(evalParam)
            }
            ];

            // Check that all criterias were successful
            var successCounter = 0;
            $.each(
                criterias,
                function (index, criteria) {

                    var successCriteria = Object.keys(criteria).filter(
                        function (criteriaKey) {

                            return criteria[criteriaKey];
                        }
                    );

                    if (successCriteria.length) {
                        successCounter++;
                    }
                }
            );
    
            return (successCounter == criterias.length);
        }
    };
})(jQuery);
