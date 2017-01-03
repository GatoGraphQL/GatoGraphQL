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

		// Get all the tabs open from the previous session, and open them already		
		$(document).on('initialized.pop.document', function() {
		// Do it on document ready to give time to initialize the interceptors
		// $(document).ready( function() {
			
			popManager.openTabs();
		});
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	showRefreshMessage : function(pageSectionPage) {

		var t = this;
		
		var url = pageSectionPage.data('url');
		var target = pageSectionPage.data('target');
		// var id = pageSectionPage.attr('id')+'-refreshmsg';
		pageSectionPage.prepend(M.SW_MESSAGE.format(url, target/*, id*/));
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popServiceWorkers, ['documentInitialized', 'pageSectionNewDOMsBeforeInitialize', 'modifyOptions', 'fetchBrowserURL', 'reopenTabs']);