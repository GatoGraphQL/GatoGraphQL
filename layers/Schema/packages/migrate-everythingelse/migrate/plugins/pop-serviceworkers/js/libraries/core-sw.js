"use strict";
(function($){
window.pop.CoreSW = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------
	resetTimestamp : function(args) {

		var that = this;
		var pageSection = args.pageSection, block = args.block;

		// Only if SW supported
		if ('serviceWorker' in navigator) {

			// Reset the timestamp in the block params to the current timestamp
			// That is because SW caches the 'html' resource, with its first timestamp, so it keeps
			// sending the request to see how many new posts there are from that pretty old date,
			// producing messages like "View new 17 posts"
			var blockQueryState = pop.Manager.getBlockQueryState(pageSection, block);

			// Timestamp is provided in seconds, function Date.now() returns in milliseconds, so make the translation
			// Also, rounding the current timestamp to the second increases chances that different users might be served the same response by hitting the cache
			// Solution taken from https://stackoverflow.com/questions/221294/how-do-you-get-a-timestamp-in-javascript
			// Also add the timezone difference in seconds, to synchronize the right time in both server and client
			blockQueryState[pop.c.URLPARAM_TIMESTAMP] = Math.floor(Date.now()/1000) + (pop.c.GMT_OFFSET * 60 * 60);
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize (but only if Service Workers enabled!)
//-------------------------------------------------
if (pop.c.USE_SW) {
	pop.JSLibraryManager.register(pop.CoreSW, ['resetTimestamp']);
}
