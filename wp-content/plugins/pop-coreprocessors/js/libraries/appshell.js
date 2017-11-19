"use strict";
(function($){
window.popAppShell = {

	strings: {},

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	fetchBrowserURL : function(args) {

		var that = this;
		
		// Do it on initialized.pop.document already, since there's no need to wait, this is the main content
		$(document).on('initialized.pop.document', function() {
			
			var options = {
				skipPushState: true,
			};

			// Comment Leo 21/05/2017: handle the homepage as a special case
			// If requesting https://getpop.org, without the locale (ie: https://getpop.org/en),
			// then add the locale always
			// This is to avoid that different caches are saved under getpop.org and getpop.org/en,
			// which sometimes makes the website get stale content from the cache, and show a message "This page has been updated"
			// even though the user had already loaded that most up-to-date content
			// Problem happens not just for homepage, but for whenever there's no locale, eg: https://getpop.org/implement
			var url = window.location.href;
			if (!url.startsWith(M.HOMELOCALE_URL+'/')) {
				url = M.HOMELOCALE_URL+url.substr(M.HOME_DOMAIN.length);
			}

			popManager.fetch(url, options);
		});
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popAppShell, ['fetchBrowserURL']);
