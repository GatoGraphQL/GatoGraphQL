(function($){
popServiceWorkers = {

	// Keep a list of all open pages, as a pair key=url value=pageSectionPage
	pages: {},

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	documentInitialized : function(args) {

		var t = this;
		if ('serviceWorker' in navigator) {
			
			// Catch the messages delivered by the Service Worker, and act upon them
			navigator.serviceWorker.onmessage = function (event) {

				var message = JSON.parse(event.data);
				if (message.type === 'refresh') {

					// If there is a pageSectionPage for that URL
					if (t.pages[message.url]) {

						// Retrieve it
						var pageSectionPage = $('#'+t.pages[message.url]);

						// Make sure the pageSectionPage is still in the DOM: if it still has its data attributes then it's there
						// If it's removed, that becomes undefined
						if (pageSectionPage && pageSectionPage.data('fetch-url')) {
						
							// Show the message, then delete the entry from the pages, it's not needed anymore
							t.showRefreshMessage(pageSectionPage);
							delete t.pages[message.url];
						}
					}
				}
			};

			// Show a message to the user when there is a new SW installed
			// Code taken from https://serviceworke.rs/immediate-claim_index_doc.html
			navigator.serviceWorker.addEventListener('controllerchange', function() {
				
				var status = $('#'+M.IDS_APPSTATUS);
				status.prepend(M.SW_MESSAGES_WEBSITEUPDATED.format(window.location.href));
			});

		}
	},

	pageSectionNewDOMsBeforeInitialize : function(args) {

		var t = this;
		var pageSection = args.pageSection, newDOMs = args.newDOMs;
		var psId = pageSection.attr('id');

		// Don't show the message in all pageSections, only in the main ones
		// Check if M.SW_MAIN_PAGESECTIONS is defined since, if the plug-in PoP Service Workers is not defined,
		// but this .js has been added to the bundle, then this will execute nevertheless and the line below will fail
		if (M.SW_MAIN_PAGESECTIONS && (M.SW_MAIN_PAGESECTIONS.indexOf(psId) > -1)) {
		
			var pageSectionPage = newDOMs.filter('.pop-pagesection-page');
			if (pageSectionPage.length) {

				// Make the pageSectionPage react when the url gets refreshed from stale json content from the Service Workers
				var url = pageSectionPage.data('fetch-url');
				if (url) {
					
					// Make the pageSectionPage refresh if the URL has a message
					t.pages[url] = pageSectionPage.attr('id');

					// If closing the tab, delete the entry
					pageSectionPage.one('destroy', function() {
				
						delete t.pages[url];
					});
				}
			}
		}
	},

	modifyOptions : function(args) {
	
		var t = this;
		var options = args.options, url = args.url, anchor = args.anchor;

		if (anchor.data('sw-networkfirst')) {
			options.params = options.params || {};
			options.params[M.SW_URLPARAM_NETWORKFIRST] = 'true';
		}
	},

	modifyFetchBlockOptions : function(args) {
	
		var t = this;
		var options = args.options, url = args.url;

		// if doing reload, load straight from the server if the user has connection (Network First strategy)
		if (options.reload) {
			options['onetime-post-data'] = options['onetime-post-data'] ? options['onetime-post-data']+'&' : '';
			options['onetime-post-data'] += M.SW_URLPARAM_NETWORKFIRST+'=true';
		}
	},

	fetchBrowserURL : function(args) {

		var t = this;
		
		// Do it on initialized.pop.document already, since there's no need to wait, this is the main content
		$(document).on('initialized.pop.document', function() {
			var options = {
				skipPushState: true,
			};
			popManager.fetch(window.location.href, options);
		});
	},

	reopenTabs : function(args) {

		var t = this;

		// Do it only if Service Workers are supported. This is not really needed to re-open the tabs,
		// but then all of them will be fetched from the network again, giving some connection trouble
		// to the user and making the initial load slow
		if ('serviceWorker' in navigator) {

			// Get all the tabs open from the previous session, and open them already		
			$(document).on('initialized.pop.document', function() {
			// Do it on document ready to give time to initialize the interceptors
			// $(document).ready( function() {

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
						var param1 = 'popServiceWorkers.openTabs()';

						// Comment Leo 03/03/2017: using window.location.href instead of popManager.getTopLevelFeedback()[M.URLPARAM_URL] because with the appshell it would produce the appshel URL ("http://localhost/en/loaders/appshell/")
						var param2 = 'popServiceWorkers.keepScreenOpenTab(\'{0}\', \'{1}\')'.format(window.location.href, M.URLPARAM_TARGET_MAIN);
						status.prepend(M.SW_MESSAGES_REOPENTABS.format(param1, param2));
						// popManager.openTabs();
					}
				}
			});
		}
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	saveCookie : function(value) {

		var t = this;

		// Save the value of the cookie, if set
		if ($('#'+M.SW_IDS_CHECKBOX_REMEMBER).attr('checked')) {
			console.log('adentro');
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
	},

	showRefreshMessage : function(pageSectionPage) {

		var t = this;
		
		var url = pageSectionPage.data('url');
		var target = pageSectionPage.data('target');
		// var id = pageSectionPage.attr('id')+'-refreshmsg';
		pageSectionPage.prepend(M.SW_MESSAGES_PAGEUPDATED.format(url, target/*, id*/));
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popServiceWorkers, ['documentInitialized', 'pageSectionNewDOMsBeforeInitialize', 'modifyOptions', 'modifyFetchBlockOptions', 'fetchBrowserURL', 'reopenTabs']);