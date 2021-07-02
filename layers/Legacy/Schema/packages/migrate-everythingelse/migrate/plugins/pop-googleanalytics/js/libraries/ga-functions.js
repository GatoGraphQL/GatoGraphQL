"use strict";
(function($){
window.pop.GA = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function(args) {

		var that = this;

		if (typeof ga != 'undefined') {

			// Allow Google Analytics to register the click
			$(document).on('urlfetched', function(e, url, options) {

				// Provide the path: remove the domain from the URL to track
				// Documentation: https://developers.google.com/analytics/devguides/collection/analyticsjs/single-page-applications
				if (!options.silentDocument) {
					ga('set', 'page', url.substr(pop.c.HOME_DOMAIN.length));
					ga('send', 'pageview');
				}
			});
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.GA, ['documentInitialized']);
