(function($){
popBrowserHistory = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	loaded : false, // loaded used to check if calling popState just after loading the page for first time (it must call saveState also, or we can't use back button)
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function() {

		var t = this;
		$(window).on("popstate", function(e) {

			// if (e && e.originalEvent && e.originalEvent.state) {
			if (t.loaded) {
				
				// Remove the previously added PushURLAtts
				// Otherwise intercept cannot find the Url, and no need for the fetch, since it will add the params once again
				// No need to push URL to the stack, since we're already moving back/forward there
				var url = t.removePushURLAtts(window.location.href);
				
				// If it is the external page, then recover the actual URL
				url = t.getApplicationURL(url);

				var options = {skipPushState: true};
				if (popURLInterceptors.getInterceptors(url).length) {
					popURLInterceptors.intercept(url, options);
				}
				else {
					popManager.fetch(url, options);
				}
			}
			else {
				// popState also gets called when first loading a website, so we also need to save the state, but not continue
				// to keep loading another page
				t.loaded = true;
			}
		});
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	getApplicationURL : function(url) {

		var t = this;

		// If it is the external page, then recover the actual URL
		// Allow popMultiDomain to inject the external URL
		var args = {
			url: url
		};
		popJSLibraryManager.execute('modifyApplicationURL', args);
		url = args.url;

		// // If it is the external page, then recover the actual URL
		// if (url.startsWith(M.EXTERNAL_URL)) {
		// 	url = decodeURIComponent(getParam(M.URLPARAM_URL, url));
		// }

		return url;
	},

	addPushURLAtts : function(url) {
	
		var t = this;
		var tlFeedback = popManager.getTopLevelFeedback(getDomain(url));
		$.each(tlFeedback[M.DATALOAD_PUSHURLATTS], function(key, value) {

			url = add_query_arg(key, value, url);
		});

		return url;
	},

	removePushURLAtts : function(url) {
	
		var t = this;

		var tlFeedback = popManager.getTopLevelFeedback(getDomain(url)), args = [];
		$.each(tlFeedback[M.DATALOAD_PUSHURLATTS], function(key, value) {

			args.push(key);
		});
		return removeQueryFields(url, args);
	},

	pushState : function(url) {
		
		var t = this;

		// After intercepting the URL, add the PUSHURLATTS, so they will also appear in the browser url
		// This is because of a problem with Chrome: when it gets restarted, it calls again the last URL it had, even within an iframe
		// So this created an issue with the embed: cannot strip param "mode=embed" in the browser URL, gotta add it again even if artificially
		url = t.addPushURLAtts(url);

		// Push history in the browser
		// Platform of Platforms: if the URL is not from this website, then add it as a param
		// Allow popMultiDomain to inject the external URL
		var args = {
			url: url
		};
		popJSLibraryManager.execute('modifyPushStateURL', args);
		url = args.url;

		// if(!url.startsWith(M.HOME_DOMAIN)) {
		// 	url = add_query_arg(M.URLPARAM_URL, encodeURIComponent(url), M.EXTERNAL_URL);
		// }
		history.pushState({url: url}, '', url);

		t.loaded = true;
	},	

	replaceState : function(url) {
		
		var t = this;

		// Only if the new url is different than the current one
		if (window.location.url == url) {
			return;
		}

		url = t.addPushURLAtts(url);

		// Push history in the browser
		history.replaceState({url: url}, '', url);

		// t.currentUrl = url;
		t.loaded = true;
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popBrowserHistory, ['documentInitialized'], true); // Make all base JS classes high priority so that they execute first