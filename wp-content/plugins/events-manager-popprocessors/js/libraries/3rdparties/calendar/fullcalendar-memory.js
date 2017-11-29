"use strict";
(function($){
window.popFullCalendarMemory = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockRuntimeMemoryIndependent : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

		// Initialize with this library key
		mempage.fullCalendar = {};

		// Reset values
		that.resetBlockRuntimeMemory(pageSection, block);
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getRuntimeMemoryPage : function(pageSection, targetOrId) {

		var that = this;
		return popManager.getRuntimeMemoryPage(pageSection, targetOrId).fullCalendar;
	},

	resetBlockRuntimeMemory : function(pageSection, targetOrId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, targetOrId);
		var empty = {

			// Save the settings for each event
			// eventData: {},

			// After rendering the html for the event, save it to use it again on popState
			eventsHtml: {},

			// Flag to indicate if, after rendering the html for the event, run JS on it
			runJS: false,

			// Save the events data
			events: [],

			// Save the ids of all events already loaded (to avoid duplication: eg: events spanning Jan / Feb are brought twice, once for Jan once for Feb)
			loadedEventIds: {},

			// Already loaded year/month (for cache)
			loadedDates: {},
		};

		$.extend(mempage, empty);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popFullCalendarMemory, ['initBlockRuntimeMemoryIndependent']);

