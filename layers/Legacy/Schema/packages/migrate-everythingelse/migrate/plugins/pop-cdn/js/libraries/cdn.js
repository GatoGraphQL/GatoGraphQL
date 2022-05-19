"use strict";
(function($){
window.pop.CDN = {

	localStorageKey : 'PoP:cdn-thumbprints',
	thumbprintValues : {},

	// This variable will be filled through cdn-config.js
	config : {},

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	documentInitialized : function(args) {
	
		var that = this;

		// Will need the thumbprint values from the topLevelFeedback
		var domain = args.domain;
		// var tlValues = pop.Manager.getTopLevelFeedback(domain)[pop.c.CDN_THUMBPRINTVALUES];
		var tlValues = pop.Manager.getSiteMeta(domain)[pop.c.CDN_THUMBPRINTVALUES];

		// Set initially the value from the localStorage, if it exists
		that.thumbprintValues = pop.Manager.getLocalStorageData(that.localStorageKey, false);

		if (that.thumbprintValues) {

			// Update with values from the topLevelFeedback
			that.updateThumbprintValues(tlValues);
		}
		else {

			// Initialize as the topLevelFeedback directly
			that.thumbprintValues = $.extend({}, tlValues);
			that.storeData();
		}
	},

	pageSectionFetchSuccess : function(args) {
	
		var that = this;
		var response = args.response;
		that.fetchSuccess(response);
	},

	blockFetchSuccess : function(args) {
	
		var that = this;
		var response = args.response;
		that.fetchSuccess(response);
	},

	modifyFetchArgs : function(args) {
	
		var that = this;
		var options = args.options, url = args.url;

		if (that.useCDN(url)) {
			
			// Modify the URL, replacing the website domain with the CDN
			args.url = that.getCDNUrl(url);
		
			// Add the thumbprints as params
			var thumbprint = that.getThumbprintValue(url);
			if (thumbprint) {
				options.params = options.params || {};
				options.params[pop.c.CDN_URLPARAM_THUMBPRINT] = thumbprint;
			}
		}
	},

	modifyFetchBlockArgs : function(args) {
	
		var that = this;
		var options = args.options, url = args.url, type = args.type;

		// Only for GET requests
		if (type == 'GET' && that.useCDN(url)) {
			
			// Modify the URL, replacing the website domain with the CDN
			args.url = that.getCDNUrl(url);
		
			// Add the thumbprints as params
			var thumbprint = that.getThumbprintValue(url);
			if (thumbprint) {
				options['onetime-post-data'] = options['onetime-post-data'] ? options['onetime-post-data']+'&' : '';
				options['onetime-post-data'] += pop.c.CDN_URLPARAM_THUMBPRINT+'='+thumbprint;
			}
		}
	},

	maybeModifyURL : function(args) {
	
		var that = this;
		args.url = that.convertURL(args.url);
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	hasConfiguration : function(url) {
  
		var that = this;
		var domain = getDomain(url);

		// Just making sure the .js file containing the configuration for pop.CDN (cdn-config.js) was generated ok. If not, fail gracefully
		if (typeof that.config[domain] == 'undefined') {

			return false;
		}

		return true;
	},

	getConfiguration : function(url) {
  
		var that = this;
		var domain = getDomain(url);
		return that.config[domain];
	},

	getThumbprints : function(url) {

		var that = this;

		if (!that.hasConfiguration(url)) {

			console.log('There is no config object, no thumbprints returned');
			return [];
		}

		var config = that.getConfiguration(url);
		var thumbprints = [];
		$.each(config.thumbprints, function(index, thumbprint) {

			if (that.evalCriteria(url, config.criteria.thumbprints[thumbprint])) {

				thumbprints.push(thumbprint);
			}
		});

		return thumbprints;
	},

	// canUseCDN : function(url) {
	
	// 	var that = this;

	// 	if (!that.hasConfiguration(url)) {

	// 		return false;
	// 	}

	// 	// Can use, if that URL is not rejected
	// 	var config = that.getConfiguration(url);
	// 	return !that.evalCriteria(url, config.criteria.rejected);
	// },

	isHome : function(url) {

		var path = removeParams(url);
		var possible = [pop.c.HOME_DOMAIN, pop.c.HOME_DOMAIN+'/', pop.c.HOMELOCALE_URL, pop.c.HOMELOCALE_URL+'/'];
		return possible.indexOf(path) > -1;
	},

	evalCriteria : function(url, entries) {
	
		var that = this;

		var evalParam = function(elem) {

			var key = elem[0], value = elem[1];
			var paramValue = getParam(key, url);

			// The parameter may be a single value, or an array. In the latter case,
			// compare that the value to check (eg: field 'postcomments') is inside the array (eg: fields[0]=postcomments&fields[1]=postauthor => ['postcomments', 'postauthor'])
			// In that case, paramValue will be an object, looking like this:
			// fields:Object
			//	 0:"recommendpost-count"
			//	 1:"recommendpost-count-plus1"
			//	 2:"userpostactivity-count"
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

				startsWith: entries.startsWith.full.some(function(path) {
					
					return url.startsWith(path);
				}),

				// The pages do not included the locale domain, so add it before doing the comparison
				pageStartsWith: entries.startsWith.partial.some(function(path) {
					
					return url.startsWith(pop.c.HOMELOCALE_URL+'/'+path);
				}),

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
		$.each(criterias, function(index, criteria) {

			var successCriteria = Object.keys(criteria).filter(function(criteriaKey) {

				return criteria[criteriaKey];
			});

			if (successCriteria.length) {
				successCounter++;
			}
		});
		
		return (successCounter == criterias.length);
	},

	fetchSuccess : function(response) {
	
		var that = this;

		// Update the thumbprint values from the response's topLevelFeedback
		that.updateThumbprintValues(response.statefuldata.feedback.toplevel[pop.c.COMPONENTSETTINGS_ENTRYCOMPONENT][pop.c.CDN_THUMBPRINTVALUES]);
	},

	updateThumbprintValues : function(newValues) {
	
		// Get the new values from newValues (which is the topLevelFeedback), and set them as the thumbprint value if they are bigger than the current value
		// This is done like this, because the topLevelFeedback may come from a page cached in Service Workers, so that that value
		// is already stale, and otherwise it would override more up-to-date values
		var that = this;

		// Flag to indicate if to save a new more up-to-date value in localStorage
		var save = false;

		// Get the max value, for each thumbprint item, from both the topLevelFeedback and the current value
		for (var key in newValues) {

			if (newValues[key] > that.thumbprintValues[key]) {

				that.thumbprintValues[key] = newValues[key];
				save = true;
			}
		}

		// Save latest thumbprint values in localStorage
		if (save) {
			that.storeData();
		}
	},

	storeData : function() {
	
		var that = this;

		pop.Manager.addLocalStorageData(that.localStorageKey, that.thumbprintValues);
	},

	useCDN : function(url) {
	
		var that = this;

		// Check there is a configuration for this domain
		if (!that.hasConfiguration(url)) {

			return false;
		}

		var config = that.getConfiguration(url);

		// Check there is a CDN domain to replace with
		if (!config.cdnDomain) {

			return false;
		}

		// Check that the URL is not rejected
		return !that.evalCriteria(url, config.criteria.rejected);
	},

	getCDNUrl : function(url) {
	
		var that = this;

		// // Replace the website domain with the CDN domain
		// return pop.c.CDN_CONTENT_URI+url.substr(pop.c.HOME_DOMAIN.length);
		var domain = getDomain(url);
		var config = that.getConfiguration(url);
		return config.cdnDomain+url.substr(domain.length);
	},

	getThumbprintValue : function(url) {
	
		var that = this;

		var thumbprints = that.getThumbprints(url);
		var value = thumbprints.map(function(thumbprint) {
			return that.thumbprintValues[thumbprint];
		});

		// Join all the thumbprints together using a dot, to make it easier to identify the different thumbprint values
		return value.join(pop.c.CDN_SEPARATOR_THUMBPRINT);
	},

	convertURL : function(url) {
	
		var that = this;

		if (that.useCDN(url)) {
			
			// Important: get the thumbprint now. After doing that.getCDNUrl(url);, the domain changes, so the thumbprint values won't be found anymore
			var thumbprint = that.getThumbprintValue(url);

			// Modify the URL, replacing the website domain with the CDN
			url = that.getCDNUrl(url);

			// Add the version
			// url = add_query_arg(pop.c.CDN_URLPARAM_VERSION, pop.c.VERSION, url);
		
			// Add the thumbprints as params
			if (thumbprint) {

				url = add_query_arg(pop.c.CDN_URLPARAM_THUMBPRINT, thumbprint, url);
			}
		}

		return url;
	},

};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.CDN, ['documentInitialized', 'pageSectionFetchSuccess', 'blockFetchSuccess'], true); // Execute before everything else, so that the right thumbprint values are already used when fetching backgroundLoad pages
pop.JSLibraryManager.register(pop.CDN, ['modifyFetchArgs', 'modifyFetchBlockArgs', 'maybeModifyURL']);
