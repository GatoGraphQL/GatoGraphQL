(function($){
popTabs = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	documentInitialized : function(args) {

		var t = this;

		// Check if there are tabs to open
		var show = false;
		var currentURL = popManager.getTabsCurrentURL();
		var tabs = popManager.getScreenOpenTabs();
		$.each(tabs, function(target, urls) {

			// Show the alert if any of the following conditions is met:
			if (
				// With at least 2 URLs, then for sure it has 1 to open
				(urls.length > 1) ||

				// It has at least 1 URL and it's not the main
				(target != M.URLPARAM_TARGET_MAIN && urls.length) ||

				// It is the main, and its URL is different to the current one
				(target == M.URLPARAM_TARGET_MAIN && urls[0] != currentURL)
				) {
				show = true;
				return -1;
			}
		});

		// Show the message?
		if (show) {

			// Get value from cookie
			if ($.cookie('opentabs')) {

				// The value is either "true" or "false"
				if ($.cookie('opentabs') == "true") {
					popManager.openTabs();
				}
				else {
					popManager.keepScreenOpenTab(window.location.href, M.URLPARAM_TARGET_MAIN);
				}
			}
			else {

				// If accepting from the dialog below, it will execute popManager.openTabs();
				var status = $('#'+M.IDS_APPSTATUS);
				var param1 = 'popTabs.openTabs()';

				// Comment Leo 03/03/2017: using window.location.href instead of popManager.getTopLevelFeedback()[M.URLPARAM_URL] because with the appshell it would produce the appshel URL ("http://localhost/en/loaders/appshell/")
				var param2 = 'popTabs.keepScreenOpenTab(\'{0}\', \'{1}\')'.format(window.location.href, M.URLPARAM_TARGET_MAIN);
				status.prepend(M.TABS_REOPENMSG.format(param1, param2));
				// popManager.openTabs();
			}
		}
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	saveCookie : function(value) {

		var t = this;

		// Save the value of the cookie, if set
		if ($('#'+M.IDS_TABS_REMEMBERCHECKBOX).attr('checked')) {
			
			$.cookie('opentabs', value, { expires: 365, path: "/" });
		}
	},

	openTabs : function() {

		var t = this;

		// Save the value of the cookie, if set
		t.saveCookie("true");

		// Open the tabs
		popManager.openTabs();
	},

	keepScreenOpenTab : function(url, target) {

		var t = this;

		// Save the value of the cookie, if set
		t.saveCookie("false");

		// Remove the previous session tabs from openTabs
		popManager.keepScreenOpenTab(url, target);
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popTabs, ['documentInitialized']);