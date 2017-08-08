(function($){
popCDN = {

	localStorageKey : 'PoP:cdn-thumbprints',
	thumbprintValues : {},

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	documentInitialized : function(args) {
	
		var t = this;

		// Will need the thumbprint values from the topLevelFeedback
		var domain = args.domain;
		var tlValues = popManager.getTopLevelFeedback(domain)[M.CDN_THUMBPRINTVALUES];

		// Set initially the value from the localStorage, if it exists
		t.thumbprintValues = popManager.getStoredData(t.localStorageKey, false);

		if (t.thumbprintValues) {

			// Update with values from the topLevelFeedback
			t.updateThumbprintValues(tlValues);
		}
		else {

			// Initialize as the topLevelFeedback directly
			t.thumbprintValues = $.extend({}, tlValues);
			t.storeData();
		}
	},

	pageSectionFetchSuccess : function(args) {
	
		var t = this;
		var response = args.response;
		t.fetchSuccess(response);
	},

	blockFetchSuccess : function(args) {
	
		var t = this;
		var response = args.response;
		t.fetchSuccess(response);
	},

	modifyFetchArgs : function(args) {
	
		var t = this;
		var options = args.options, url = args.url;

		if (t.useCDN(url)) {
			
			// Modify the URL, replacing the website domain with the CDN
			args.url = t.getCDNUrl(url);
		
			// Add the thumbprints as params
			var thumbprint = t.getThumbprintValue(url);
			if (thumbprint) {
				options.params = options.params || {};
				options.params[M.CDN_URLPARAM_THUMBPRINT] = thumbprint;
			}
		}
	},

	modifyFetchBlockArgs : function(args) {
	
		var t = this;
		var options = args.options, url = args.url, type = args.type;

		// Only for GET requests
		if (type == 'GET' && t.useCDN(url)) {
			
			// Modify the URL, replacing the website domain with the CDN
			args.url = t.getCDNUrl(url);
		
			// Add the thumbprints as params
			var thumbprint = t.getThumbprintValue(url);
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
	
		var t = this;

		// Update the thumbprint values from the response's topLevelFeedback
		t.updateThumbprintValues(response.feedback.toplevel[M.CDN_THUMBPRINTVALUES]);
	},

	updateThumbprintValues : function(newValues) {
	
		// Get the new values from newValues (which is the topLevelFeedback), and set them as the thumbprint value if they are bigger than the current value
		// This is done like this, because the topLevelFeedback may come from a page cached in Service Workers, so that that value
		// is already stale, and otherwise it would override more up-to-date values
		var t = this;

		// Flag to indicate if to save a new more up-to-date value in localStorage
		var save = false;

		// Get the max value, for each thumbprint item, from both the topLevelFeedback and the current value
		for (var key in newValues) {

			if (newValues[key] > t.thumbprintValues[key]) {

				t.thumbprintValues[key] = newValues[key];
				save = true;
			}
		}

		// Save latest thumbprint values in localStorage
		if (save) {
			t.storeData();
		}
	},

	storeData : function() {
	
		var t = this;

		popManager.storeData(t.localStorageKey, t.thumbprintValues);
	},

	useCDN : function(url) {
	
		var t = this;

		// Use CDN if:
		// - CDN URL is defined, 
		// - the URL is pointing locally (eg: not decentralized), then can use CDN
		// - the URL allows to use CDN (eg: page_requires_user_state) do not
		return M.CDN_CONTENT_URI && url.startsWith(M.HOME_DOMAIN) && popCDNThumbprints.canUseCDN(url);
	},

	getCDNUrl : function(url) {
	
		var t = this;

		// Replace the website domain with the CDN domain
		return M.CDN_CONTENT_URI+url.substr(M.HOME_DOMAIN.length);
	},

	getThumbprintValue : function(url) {
	
		var t = this;

		var thumbprints = popCDNThumbprints.getThumbprints(url);
		var value = [];
		
		$.each(thumbprints, function(index, thumbprint) {

			// Add the value of that thumbprint
			value.push(t.thumbprintValues[thumbprint]);
		});

		// Split them with a separator to make it easier to see the different thumbprint values
		return value.join(M.CDN_SEPARATOR_THUMBPRINT);
	},

	convertURL : function(url) {
	
		var t = this;

		if (t.useCDN(url)) {
			
			// Important: get the thumbprint now. After doing t.getCDNUrl(url);, the domain changes, so the thumbprint values won't be found anymore
			var thumbprint = t.getThumbprintValue(url);

			// Modify the URL, replacing the website domain with the CDN
			url = t.getCDNUrl(url);

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
