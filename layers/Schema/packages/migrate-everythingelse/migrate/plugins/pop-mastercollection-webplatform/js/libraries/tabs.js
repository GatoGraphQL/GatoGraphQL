"use strict";
(function($){
window.pop.Tabs = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	documentInitialized : function(args) {

		var that = this;

		// Wait until all JS config files from the ResourceLoader have been loaded
		$(document).ready(function($){
			// Check if there are tabs to open
			var show = false;
			var currentURL = pop.Manager.getTabsCurrentURL();
			var tabs = pop.Manager.getScreenOpenTabs();
			$.each(tabs, function(target, urls) {

				// Show the alert if any of the following conditions is met:
				if (
					// With at least 2 URLs, then for sure it has 1 to open
					(urls.length > 1) ||

					// It has at least 1 URL and it's not the main
					(target != pop.c.URLPARAM_TARGET_MAIN && urls.length) ||

					// It is the main, and its URL is different to the current one
					(target == pop.c.URLPARAM_TARGET_MAIN && urls[0] != currentURL)
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
						pop.Manager.openTabs();
					}
					else {
						pop.Manager.keepScreenOpenTab(pop.Manager.getTabsCurrentURL(), pop.c.URLPARAM_TARGET_MAIN);
					}
				}
				else {

					// If accepting from the dialog below, it will execute pop.Manager.openTabs();
					var status = $('#'+pop.c.IDS_APPSTATUS);
					var param1 = 'pop.Tabs.openTabs()';

					// Comment Leo 03/03/2017: using window.location.href instead of pop.Manager.getTopLevelFeedback()[pop.c.URLPARAM_URL] because with the appshell it would produce the appshel URL ("http://localhost/en/loaders/appshell/")
					// Comment Leo 16/03/2017: use pop.Manager.getTabsCurrentURL() instead of window.location.href, so that if it is https://getpop.org it gets transformed to https://getpop.org/en/, or otherwise the message asking to reopen the tabs, when loading the homepage, keeps appearing
					var param2 = 'pop.Tabs.keepScreenOpenTab(\'{0}\', \'{1}\')'.format(pop.Manager.getTabsCurrentURL(), pop.c.URLPARAM_TARGET_MAIN);
					status.prepend(pop.c.TABS_REOPENMSG.format(param1, param2));
					// pop.Manager.openTabs();
				}
			}
		});
	},

	onDestroyPageSwitchTab : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		// Catch the event 'destroy' and switch the tab then. This way, it will also work when destroying the pageSectionPage
		// not just fron clicking on this button but also other sources, eg: the self-closing of the addon pageSection after submitting a comment
		targets.each(function() {
			
			var link = $(this);
			var pageSectionPage = pop.Manager.getPageSectionPage(link);
			pageSectionPage.on('destroy', function() {

				that.switchTab(link);
			})
		})
	},

	addOpenTab : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets, addOpenTab = args.addOpenTab;

		// Allow function openTabs to set this attr in false, so this value is not added yet once again since it's already open
		// It's needed for opening Add Post pages, which have different hashtags to differentiate them, so otherwise opening one Add Page
		// and reloading the page will open one page again, then 2, then 4, etc
		if (addOpenTab === false) {
			return;
		}

		targets.each(function() {
			
			var link = $(this);
			
			var params = that.getDestroyPageTabParams(pageSection, link);

			// Add the url to the session tabs, so next session they can be re-opened
			pop.Manager.addOpenTab(params.url, params.target);
		});
	},

	closePageTab : function(args) {

		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {
			
			e.preventDefault();
			var link = $(this);
			
			var params = that.getDestroyPageTabParams(pageSection, link);

			// Comment Leo 10/01/2017: this line has been moved to triggerDestroyTarget so it's executed even
			// if the user did not click on the close button, eg: after adding a comment, the window self destroys,
			// but the Add Comment tab remained open for the next session!
			// // Remove the tab from the open sessions
			// pop.Manager.removeOpenTab(params.url, params.target);

			// destroy the PageTab
			pop.Manager.triggerDestroyTarget(params.url, params.target);
		});
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	getDestroyPageTabParams : function(pageSection, link) {

		var that = this;
			
		// URL: take it from 'data-url' on the link (eg: Page Tab), or if it doesn't exist,
		// from the corresponding paramsscope URL (eg: frame controls to close a page)
		return {
			url: link.data('url') || pop.Manager.getTargetParamsScopeURL(pop.Manager.getBlock(link))/*pop.Manager.getBlock(link).data('paramsscope-url')*/,
			target: link.attr('target') || pop.Manager.getFrameTarget(pageSection)
		};
	},

	switchTab : function(link) {

		var that = this;

		// If the link is active, then switch to previous or next tab
		if (link.prev('a.pop-pagetab-btn').hasClass('active')) {

			var current = pop.Manager.getPageSectionPage(link);

			// Can't use current.prev() because sometimes it's a '.pop-pagesection-page' object, sometimes it's an interceptor
			var next = current.nextAll('.pop-pagesection-page').first();
			if (!next.length) {
				next = current.prevAll('.pop-pagesection-page').first();
			}

			if (next.length) {
				// If another tab is available, click on it to open it
				next.find('a.pop-pagetab-btn').trigger('click');
			}
		}
	},

	saveCookie : function(value) {

		var that = this;

		// Save the value of the cookie, if set
		if ($('#'+pop.c.IDS_TABS_REMEMBERCHECKBOX).prop('checked')) {
			
			$.cookie('opentabs', value, { expires: 365, path: "/" });
		}
	},

	openTabs : function() {

		var that = this;

		// Save the value of the cookie, if set
		that.saveCookie("true");

		// Open the tabs
		pop.Manager.openTabs();
	},

	keepScreenOpenTab : function(url, target) {

		var that = this;

		// Save the value of the cookie, if set
		that.saveCookie("false");

		// Remove the previous session tabs from openTabs
		pop.Manager.keepScreenOpenTab(url, target);
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// Only if enabled
if (pop.c.OPENTABS) {
	pop.JSLibraryManager.register(pop.Tabs, ['documentInitialized', 'onDestroyPageSwitchTab', 'addOpenTab', 'closePageTab']);
}
