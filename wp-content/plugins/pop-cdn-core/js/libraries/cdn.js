"use strict";
(function($){
window.popCDN = {

	localStorageKey : 'PoP:cdn-thumbprints',
	thumbprintValues : {},

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	documentInitialized : function(args) {
	
		var that = this;

		// Will need the thumbprint values from the topLevelFeedback
		var domain = args.domain;
		var tlValues = popManager.getTopLevelFeedback(domain)[M.CDN_THUMBPRINTVALUES];

		// Set initially the value from the localStorage, if it exists
		that.thumbprintValues = popManager.getStoredData(that.localStorageKey, false);

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
				options.params[M.CDN_URLPARAM_THUMBPRINT] = thumbprint;
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
				options['onetime-post-data'] += M.CDN_URLPARAM_THUMBPRINT+'='+thumbprint;
			}
		}
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	fetchSuccess : function(response) {
	
		var that = this;

		// Update the thumbprint values from the response's topLevelFeedback
		that.updateThumbprintValues(response.feedback.toplevel[M.CDN_THUMBPRINTVALUES]);
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

		popManager.storeData(that.localStorageKey, that.thumbprintValues);
	},

	useCDN : function(url) {
	
		var that = this;

		// Use CDN if:
		// - CDN URL is defined, 
		// - the URL is pointing locally (eg: not decentralized), then can use CDN
		// - the URL allows to use CDN (eg: page_requires_user_state) do not
		return M.CDN_CONTENT_URI && url.startsWith(M.HOME_DOMAIN) && popCDNThumbprints.canUseCDN(url);
	},

	getCDNUrl : function(url) {
	
		var that = this;

		// Replace the website domain with the CDN domain
		return M.CDN_CONTENT_URI+url.substr(M.HOME_DOMAIN.length);
	},

	getThumbprintValue : function(url) {
	
		var that = this;

		var thumbprints = popCDNThumbprints.getThumbprints(url);
		// var value = [];
		// $.each(thumbprints, function(index, thumbprint) {

		// 	// Add the value of that thumbprint
		// 	value.push(that.thumbprintValues[thumbprint]);
		// });
		var value = thumbprints.map(function(thumbprint) {
			return that.thumbprintValues[thumbprint];
		});

		// Join all the thumbprints together using a dot, to make it easier to identify the different thumbprint values
		return value.join(M.CDN_SEPARATOR_THUMBPRINT);
	},

	convertURL : function(url) {
	
		var that = this;

		if (that.useCDN(url)) {
			
			// Important: get the thumbprint now. After doing that.getCDNUrl(url);, the domain changes, so the thumbprint values won't be found anymore
			var thumbprint = that.getThumbprintValue(url);

			// Modify the URL, replacing the website domain with the CDN
			url = that.getCDNUrl(url);

			// Add the version
			// url = add_query_arg(M.CDN_URLPARAM_VERSION, M.VERSION, url);
		
			// Add the thumbprints as params
			if (thumbprint) {

				url = add_query_arg(M.CDN_URLPARAM_THUMBPRINT, thumbprint, url);
			}
		}

		return url;
	},

};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popCDN, ['documentInitialized', 'pageSectionFetchSuccess', 'blockFetchSuccess'], true); // Execute before everything else, so that the right thumbprint values are already used when fetching backgroundLoad pages
popJSLibraryManager.register(popCDN, ['modifyFetchArgs', 'modifyFetchBlockArgs']);
