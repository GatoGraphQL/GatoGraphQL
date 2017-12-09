"use strict";
(function($){
window.popFullCalendarAddEvents = {

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	addEvents : function(pageSection, block, /*calendar, */events_data) {
	
		var that = this;

		// Needed to not initialize the Navigator Calendar initially. Otherwise it produces a JS error since the mempage was never initialized
		if (!events_data.length) return;

		// When the block is not initialized, we can't add the events since the runtimeMemory is not ready yet
		// So then wait until the calendar is initialized, and only then add the events
		if (popManager.jsInitialized(block)) {
			that.execAddEvents(pageSection, block, /*calendar, */events_data);
		}
		else {
			block.one('initialize', function() {
				that.execAddEvents(pageSection, block, /*calendar, */events_data);
			});
		}
	},
	// addEvents : function(blockId, events_data) {
	
	// 	var that = this;

	// 	// Needed to not initialize the Navigator Calendar initially. Otherwise it produces a JS error since the mempage was never initialized
	// 	if (!events_data.length) return;

	// 	var block = popManager.getBlock($('#'+blockId));
	// 	// If the block doesn't exist yet, then execute the function again once it does exist
	// 	if (typeof block == 'undefined') {

	// 		$(document).one('template:merged', function() {

	// 			that.addEvents(blockId, events_data);
	// 		});
	// 		return;		
	// 	}
		
	// 	var pageSection = popManager.getPageSection(block);

	// 	// When the block is not initialized, we can't add the events since the runtimeMemory is not ready yet
	// 	// So then wait until the calendar is initialized, and only then add the events
	// 	if (popManager.jsInitialized(block)) {
	// 		that.execAddEvents(pageSection, block, /*calendar, */events_data);
	// 	}
	// 	else {
	// 		block.one('initialize', function() {
	// 			that.execAddEvents(pageSection, block, /*calendar, */events_data);
	// 		});
	// 	}
	// },

	execAddEvents : function(pageSection, block, /*calendar, */events_data) {
	
		var that = this;

		var mempage = popFullCalendarMemory.getRuntimeMemoryPage(pageSection, block);
		
		// Filter events to be added: check they have not been added already (eg: events spanning Jan/Feb)
		var events_data_to_add = [];

		// Foreach event, save the configuration
		$.each(events_data, function(index, event_data) {

			// Check if the event has not been added yet
			if (!that.isEventLoaded(pageSection, block, event_data.domain, event_data.id)) {

				// mempage.eventData[event_data.id] = {
					// itemDBKey: itemDBKey
				// };

				events_data_to_add.push(event_data);

				// Mark this event as loaded
				that.setEventLoaded(pageSection, block, event_data.domain, event_data.id);
			}
		});

		$.merge(mempage.events, events_data_to_add);

		// Trigger a handler to re-draw the calendar
		block.triggerHandler('addedEvents');
	},

	setEventLoaded : function(pageSection, block, domain, eventId) {

		var that = this;
		var mempage = popFullCalendarMemory.getRuntimeMemoryPage(pageSection, block);

		mempage.loadedEventIds[domain] = mempage.loadedEventIds[domain] || {};
		mempage.loadedEventIds[domain][eventId] = true;
	},

	isEventLoaded : function(pageSection, block, domain, eventId) {

		var that = this;
		var mempage = popFullCalendarMemory.getRuntimeMemoryPage(pageSection, block);

		return mempage.loadedEventIds[domain] && mempage.loadedEventIds[domain][eventId] === true;
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// popJSLibraryManager.register(popFullCalendarAddEvents, []);

