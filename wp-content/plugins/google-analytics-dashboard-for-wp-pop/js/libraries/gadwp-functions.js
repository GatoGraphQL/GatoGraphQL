"use strict";
(function($){
window.popGADWP = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	// pageSectionFetchSuccess : function(args) {

	// 	var that = this;

	// 	// Only register the Google Analytics call if it is not a silent page
	// 	var options = args.options;
	// 	if (!options.silentDocument) {

	// 		ga('send', 'pageview');
	// 	}
	// },
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
// popJSLibraryManager.register(popGADWP, ['pageSectionFetchSuccess']);
popJSLibraryManager.register(popGADWP, ['documentInitialized']);
