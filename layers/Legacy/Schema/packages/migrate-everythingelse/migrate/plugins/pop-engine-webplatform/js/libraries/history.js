"use strict";
(function($){
window.pop.BrowserHistory = {

	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------

	loaded : false, // loaded used to check if calling popstate just after loading the page for first time (it must call saveState also, or we can't use back button)
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function() {

		var that = this;
		$(window).on("popstate", function(e) {

			// if (e && e.originalEvent && e.originalEvent.state) {
			if (that.loaded) {
				
				// Remove the previously added PushURLAtts
				// Otherwise intercept cannot find the Url, and no need for the fetch, since it will add the params once again
				// No need to push URL to the stack, since we're already moving back/forward there
				var url = that.removePushURLAtts(window.location.href);
				
				// If it is the external page, then recover the actual URL
				url = that.getApplicationURL(url);

				var options = {skipPushState: true};
				if (pop.URLInterceptors.getInterceptors(url).length) {
					pop.URLInterceptors.intercept(url, options);
				}
				else {
					pop.Manager.fetch(url, options);
				}
			}
			else {
				// popstate also gets called when first loading a website, so we also need to save the state, but not continue
				// to keep loading another page
				that.loaded = true;
			}
		});
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	getApplicationURL : function(url) {

		var that = this;

		// If it is the external page, then recover the actual URL
		// Allow pop.MultiDomain to inject the external URL
		var args = {
			url: url
		};
		pop.JSLibraryManager.execute('modifyApplicationURL', args);
		url = args.url;

		// // If it is the external page, then recover the actual URL
		// if (url.startsWith(pop.c.EXTERNAL_URL)) {
		// 	url = decodeURIComponent(getParam(pop.c.URLPARAM_URL, url));
		// }

		return url;
	},

	addPushURLAtts : function(url) {
	
		var that = this;
		// var tlFeedback = pop.Manager.getTopLevelFeedback(getDomain(url));
		// $.each(tlFeedback[pop.c.DATALOAD_PUSHURLATTS], function(key, value) {
		var siteMeta = pop.Manager.getSiteMeta(getDomain(url));
		$.each(siteMeta[pop.c.DATALOAD_PUSHURLATTS] || {}, function(key, value) {

			url = add_query_arg(key, value, url);
		});

		return url;
	},

	removePushURLAtts : function(url) {
	
		var that = this;

		var args = [];
		// var tlFeedback = pop.Manager.getTopLevelFeedback(getDomain(url));
		// $.each(tlFeedback[pop.c.DATALOAD_PUSHURLATTS], function(key, value) {
		var siteMeta = pop.Manager.getSiteMeta(getDomain(url));
		$.each(siteMeta[pop.c.DATALOAD_PUSHURLATTS] || {}, function(key, value) {

			args.push(key);
		});
		return removeQueryFields(url, args);
	},

	pushState : function(url) {
		
		var that = this;

		// After intercepting the URL, add the PUSHURLATTS, so they will also appear in the browser url
		// This is because of a problem with Chrome: when it gets restarted, it calls again the last URL it had, even within an iframe
		// So this created an issue with the embed: cannot strip param "mode=embed" in the browser URL, gotta add it again even if artificially
		url = that.addPushURLAtts(url);

		// Push history in the browser
		// Platform of Platforms: if the URL is not from this website, then add it as a param
		// Allow pop.MultiDomain to inject the external URL
		var args = {
			url: url
		};
		pop.JSLibraryManager.execute('modifyPushStateURL', args);
		url = args.url;

		// if(!url.startsWith(pop.c.HOME_DOMAIN)) {
		// 	url = add_query_arg(pop.c.URLPARAM_URL, encodeURIComponent(url), pop.c.EXTERNAL_URL);
		// }
		history.pushState({url: url}, '', url);

		// // Allow Google Analytics to register the click
		// pop.JSLibraryManager.execute('stateURLPushed', args);

		that.loaded = true;
	},	

	replaceState : function(url) {
		
		var that = this;

		// Only if the new url is different than the current one
		if (window.location.url == url) {
			return;
		}

		url = that.addPushURLAtts(url);

		// Push history in the browser
		history.replaceState({url: url}, '', url);

		// that.currentUrl = url;
		that.loaded = true;
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BrowserHistory, ['documentInitialized'], true); // Make all base JS classes high priority so that they execute first