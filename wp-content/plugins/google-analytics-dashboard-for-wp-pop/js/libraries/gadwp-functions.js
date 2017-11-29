"use strict";
(function($){
window.popGADWP = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	documentInitialized : function(args) {

		var that = this;

		// Allow Google Analytics to register the click
		$(document).on('urlfetched', function(e, url, options) {

			// Provide the path: remove the domain from the URL to track
			// Documentation: https://developers.google.com/analytics/devguides/collection/analyticsjs/single-page-applications
			if (!options.silentDocument) {
				ga('set', 'page', url.substr(M.HOME_DOMAIN.length));
				ga('send', 'pageview');
			}
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popGADWP, ['documentInitialized']);
