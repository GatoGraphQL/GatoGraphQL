"use strict";
(function($){
window.popServiceWorkers = {

	// Keep a list of all open pages, as a pair key=url value=pageSectionPage
	pages: {},

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	// Comment Leo 14/11/2017: execute function immediately
	// Otherwise, when first loading the page, if it has changed, it will not show the message "Please click here to reload the page",
	// since the code to execute that is executed after the SW 'refresh' function...
	// documentInitialized : function(args) {
	init : function() {

		var that = this;
		if ('serviceWorker' in navigator) {
			
			// Catch the messages delivered by the Service Worker, and act upon them
			navigator.serviceWorker.onmessage = function (event) {

				that.processEvent(event);
			};

			// Show a message to the user when there is a new SW installed
			// Code taken from https://serviceworke.rs/immediate-claim_index_doc.html
			// Do it only if there is a controller currently, so that it doesn't show the message the first time the controller is installed...
			if (navigator.serviceWorker.controller) {
				navigator.serviceWorker.addEventListener('controllerchange', function() {
					
					var status = $('#'+M.IDS_APPSTATUS);
					status.prepend(M.SW_MESSAGES_WEBSITEUPDATED.format(window.location.href));
				});
			}
		}
	},

	pageSectionNewDOMsBeforeInitialize : function(args) {

		var that = this;
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
					that.pages[url] = pageSectionPage.attr('id');

					// If closing the tab, delete the entry
					pageSectionPage.one('destroy', function() {
				
						delete that.pages[url];
					});
				}
			}
		}
	},

	modifyFetchArgs : function(args) {
	
		var that = this;
		var options = args.options;//, anchor = args.anchor;

		if (options['js-args']) {
			
			var anchor = options['js-args'].relatedTarget;

			if (anchor && anchor.data('sw-networkfirst')) {
				options.params = options.params || {};
				options.params[M.SW_URLPARAM_NETWORKFIRST] = 'true';
			}
		}
	},

	modifyFetchBlockArgs : function(args) {
	
		var that = this;
		var options = args.options, url = args.url;

		// if doing reload, load straight from the server if the user has connection (Network First strategy)
		if (options.reload) {
			options['onetime-post-data'] = options['onetime-post-data'] ? options['onetime-post-data']+'&' : '';
			options['onetime-post-data'] += M.SW_URLPARAM_NETWORKFIRST+'=true';
		}
	},

	resetTimestamp : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block;

		// Only if SW supported
		if ('serviceWorker' in navigator) {

			// Reset the timestamp in the block params to the current timestamp
			// That is because SW caches the 'html' resource, with its first timestamp, so it keeps
			// sending the request to see how many new posts there are from that pretty old date,
			// producing messages like "View new 17 posts"
			var blockQueryState = popManager.getBlockQueryState(pageSection, block);

			// Timestamp is provided in seconds, function Date.now() returns in milliseconds, so make the translation
			// Also, rounding the current timestamp to the second increases chances that different users might be served the same response by hitting the cache
			// Solution taken from https://stackoverflow.com/questions/221294/how-do-you-get-a-timestamp-in-javascript
			// Also add the timezone difference in seconds, to synchronize the right time in both server and client
			blockQueryState[M.URLPARAM_TIMESTAMP] = Math.floor(Date.now()/1000) + (M.GMT_OFFSET * 60 * 60);
		}
	},

	//-------------------------------------------------
	// PRIVATE functions
	//-------------------------------------------------

	processEvent : function(event) {

		var that = this;
		var message = JSON.parse(event.data);
		if (message.type === 'refresh') {

			// If there is a pageSectionPage for that URL
			if (that.pages[message.url]) {

				// Retrieve it
				var pageSectionPage = $('#'+that.pages[message.url]);

				// Make sure the pageSectionPage is still in the DOM: if it still has its data attributes then it's there
				// If it's removed, that becomes undefined
				if (pageSectionPage && pageSectionPage.data('fetch-url')) {
				
					// Show the message, then delete the entry from the pages, it's not needed anymore
					that.showRefreshMessage(pageSectionPage);
					delete that.pages[message.url];
				}
			}
		}
	},

	showRefreshMessage : function(pageSectionPage) {

		var that = this;
		
		var url = pageSectionPage.data('url');
		var target = pageSectionPage.data('target');
		pageSectionPage.prepend(M.SW_MESSAGES_PAGEUPDATED.format(url, target));
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize (but only if Service Workers enabled!)
//-------------------------------------------------
if (M.USE_SW) {
	popJSLibraryManager.register(popServiceWorkers, [/*'documentInitialized', */'pageSectionNewDOMsBeforeInitialize', 'modifyFetchArgs', 'modifyFetchBlockArgs', 'resetTimestamp']);
	popServiceWorkers.init();
}
