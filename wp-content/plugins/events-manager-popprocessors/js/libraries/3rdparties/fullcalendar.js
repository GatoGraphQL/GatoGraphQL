"use strict";
(function($){
window.popFullCalendar = {

	// domain : '',
	// promise : false,

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	initBlockRuntimeMemory : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, mempage = args.runtimeMempage;

		// Initialize with this library key
		mempage.fullCalendar = {};

		// Reset values
		that.resetBlockRuntimeMemory(pageSection, block);
	},
		
	calendar : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		var pageSectionPage = popManager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			targets.fullCalendar('destroy');
		});
		
		// If when opening the page, the pageSectionPage is not active (eg: alt+click on the link), then the calendar will not show up
		// So gotta call method 'render' whenever that page is first opened
		if (pageSectionPage.hasClass('tab-pane') && !popManager.isActive(pageSectionPage)) {

			// if (pageSectionPage.hasClass('tab-pane')) {

			pageSectionPage.one('shown.bs.tabpane', function() {
				
				targets.fullCalendar('render');
			});
			// }
		}

		// When re-rendering a block, check if the operation is REPLACE (= filtering), then delete all the current events
		block.on('beforeRender', function(e, options) {

			var block = $(this);
			
			if (options.operation == M.URLPARAM_OPERATION_REPLACE) {
	
				that.resetBlockRuntimeMemory(pageSection, block);
			}
		});

		// // block.on('fetched', function(e) {
		block.on('rendered', function(e, newDOMs, targetContainers, renderedDomain) {

			var block = $(this);
			var pageSection = popManager.getPageSection(block);
			
			// If it has an aggregator, then the event was added to that one
			// var aggregatorData = popManager.getAggregatorBlockData(pageSection, block);
			// if (aggregatorData) {
			// 	block = $('#'+aggregatorData['id']);
			// }
			
			that.execCalendar(renderedDomain, pageSection, block, targets, 'update');

			// Dispatch a window resize so that the Calendar / Google map gets updated
			windowResize();
		});

		that.execCalendar(domain, pageSection, block, targets, 'new');
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

	execAddEvents : function(pageSection, block, /*calendar, */events_data) {
	
		var that = this;

		var mempage = that.getRuntimeMemoryPage(pageSection, block);
		
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
	},

	execCalendar : function(domain, pageSection, block, targets, state) {

		var that = this;

		var mempage = that.getRuntimeMemoryPage(pageSection, block);
		var date = that.getDate(pageSection, block);

		var options;
		if (state == 'new') {
			// Options: http://arshaw.com/fullcalendar/docs/display/weekMode/
			options = {
				header: {
					left: 'title',
					center: '',
					right: ''
				},
				events: function(start, end, timezone, callback) {

					// If this block is an aggregator, then fetch the data from its subscribed blocks
					var events;
					// var subscribedBlocksData = popManager.getAggregatorSubscribedBlocksData(pageSection, block);
					// if (subscribedBlocksData) {

					// 	events = [];
					// 	$.each(subscribedBlocksData, function(index, subscribedBlockData) {

					// 		$.merge(events, that.getRuntimeMemoryPage(pageSection, subscribedBlockData['settings-id']).events);
					// 	});
					// }
					// else {
					events = mempage.events;
					// }

					callback(events);
				},
				defaultDate: date,
				fixedWeekCount: false,
				height: 'auto',
				contentHeight: 'auto',
				dayNamesShort: M.DATERANGE_DAYSOFWEEK // Change days of week from 'Mon' to 'Mo' so that it fits fine on small screens
			};
		}

		targets.each(function() {

			var calendar = $(this);

			// // Use promises, because we need to keep the domain in internal variable that.domain
			// // So through promises, we make sure that 2 executions do not change that.domain concurrently, leading to conflict
			// var dfd = $.Deferred();
			// var lastPromise = that.promise;
			// that.promise = dfd.promise();

			// if (lastPromise) {
			// 	lastPromise.done(function() {

			// Create fullCalendar elements
			that.execFullCalendar(domain, pageSection, block, calendar, state, options);
			// 	});
			// }
			// else {
			// 	// Create fullCalendar elements
			// 	that.execFullCalendar(domain, pageSection, block, calendar, state, options);
			// }

			// // Resolve the deferred
			// dfd.resolve();
		});

		// Set this year/month as loaded
		that.setDateLoaded(pageSection, block, date);
	},

	execFullCalendar : function(domain, pageSection, block, calendar, state, options) {

		var that = this;

		// We keep the domain in an internal variable. 
		// It will be accessed in functions renderEvent and renderEventAfterAll
		// that.domain = domain;

		// Create fullCalendar elements
		if (state == 'new') {
			
			// Integrate the custom options for the Calendar
			var jsSettings = popManager.getJsSettings(domain, pageSection, block, calendar);
			var customOptions = jsSettings.options || {};
			var calendarOptions = $.extend({}, options, customOptions);
			var layouts = jsSettings.layouts;

			calendarOptions.eventRender = function(event, element) {
				
				that.renderEvent(/*domain, */pageSection, block, layouts, event, element);
			};
			calendarOptions.eventAfterAllRender = function(view) {

				that.renderEventAfterAll(/*domain, */pageSection, block, layouts);
			};

			calendar.fullCalendar(calendarOptions);
		}
		else if (state == 'update') {

			calendar.fullCalendar('refetchEvents');
		}
	},

	setDateLoaded : function(pageSection, block, date) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		mempage.loadedDates[date.year()+'-'+(date.month()+1)] = true;
	},

	isDateLoaded : function(pageSection, block, date) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		return mempage.loadedDates[date.year()+'-'+(date.month()+1)] === true;
	},

	setEventLoaded : function(pageSection, block, domain, eventId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		mempage.loadedEventIds[domain] = mempage.loadedEventIds[domain] || {};
		mempage.loadedEventIds[domain][eventId] = true;
	},

	isEventLoaded : function(pageSection, block, domain, eventId) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		return mempage.loadedEventIds[domain] && mempage.loadedEventIds[domain][eventId] === true;
	},

	getDate : function(pageSection, block) {

		var that = this;
		var blockQueryState = popManager.getBlockQueryState(pageSection, block);

		// Make sure to not include the leading zeros
		var year = parseInt(blockQueryState[M.DATALOAD_VISIBLEPARAMS][M.URLPARAM_YEAR]);
		var month = parseInt(blockQueryState[M.DATALOAD_VISIBLEPARAMS][M.URLPARAM_MONTH]);

		// Migration to FullCalendar 2.0: use Moment to generate the date
		// http://arshaw.com/fullcalendar/docs/current_date/defaultDate/
		return moment(year + '-' + month + '-01', 'YYYY-M-DD');
	},

	renderEvent : function(/*domain, */pageSection, block, layouts, event, element) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		// If the html has already been generated, then use it (eg: on popState)
		// Otherwise it can't be reproduced, since the Database is already lost
		// var eventData = mempage.eventData[event.id];
		var domain = event.domain;
		var eventId = event.id;
		mempage.eventsHtml[domain] = mempage.eventsHtml[domain] || {};
		var html = mempage.eventsHtml[domain][eventId];
		if (html) {

			// Flag that we must run JS methods on the html that already exists (because it was taken out of the DOM, the methods must be run on it again)
			mempage.runJS = true;
		}
		else {

			var options = {}
			
			var dbKeys = popManager.getDatabaseKeys(domain/*getDomain(block.data('toplevel-url'))*/, pageSection, block);
			var itemDBKey = dbKeys['db-key'];
			html = '';
			popJSRuntimeManager.setBlockURL(block/*block.data('toplevel-url')*/);
			// var domain = block.data('domain') || getDomain(block.data('toplevel-url'));
			$.each(layouts, function(index, layout) {
				html += popManager.getTemplateHtml(domain, pageSection, block, layout, options, itemDBKey, eventId);
			});

			// Save the html for next time
			mempage.eventsHtml[domain][eventId] = html;
		}

		element.html(html);
	},

	renderEventAfterAll : function(/*domain, */pageSection, block, layouts) {

		var that = this;
		var mempage = that.getRuntimeMemoryPage(pageSection, block);

		// Upon triggering merged, the popover scripts with code $(document).one('template:merged', ... will get executed
		// run this before executing the JS
		popManager.triggerHTMLMerged();

		// If the date is already loaded, then the corresponding html is also already generated, then
		// we need to run the javascript once again starting on the template
		if (mempage.runJS) {

			// Clear flag
			mempage.runJS = false;
			
			// Use 'full' because just the 'last' session ids is not good enough: these will only hold the ids
			// for the last retrieved month. Eg: we're in july and the 'last' ids are for july, then we move to august,
			// 'last' ids will be august, clicking prev back to July we don't have the july ids anymore on 'last'
			// By executing on 'full', we'll be executing over absolutely all generated ids, ie: both july and august. However,
			// because august events were taken out of the DOM, then it won't execute anything on them; august events 
			// will not exist on the calendar anymore by then
			var query_urls = popManager.getQueryMultiDomainUrls(pageSection, block);
			$.each(query_urls, function(domain, query_url) {
				$.each(layouts, function(index, layout) {
					popManager.runJSMethods(domain, pageSection, block, layout, 'full');
				});
			});
		}
	},

	fetch : function(pageSection, block, calendar) {

		var that = this;

		var date = that.getDate(pageSection, block);
		// var calendar = block.find('.make-fullcalendar');			
		// Month is 0-based (http://arshaw.com/fullcalendar/docs/current_date/gotoDate/) so substract 1		
		calendar.fullCalendar( 'gotoDate', date);

		if (that.isDateLoaded(pageSection, block, date)) {

			return;
		}

		// Submit to fetch the Block data, when it comes back process it through a handler on the gdFullCalendar side
		popManager.fetchBlock(pageSection, block, {operation: M.URLPARAM_OPERATION_APPEND});
	}
};
})(jQuery);

(function($){
window.popFullCalendarControls = {
	
	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	controlCalendarPrev : function(args) {
	
		var that = this;

		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.click(function(e) {

			var control = $(this);
			that.execute(pageSection, block, control, 'prev');			
		});
	},
	controlCalendarNext : function(args) {
	
		var that = this;

		var pageSection = args.pageSection, block = args.block, targets = args.targets;
		targets.click(function(e) {

			var control = $(this);
			that.execute(pageSection, block, control, 'next');			
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	setCalendarBlockParams : function(pageSection, block, date) {

		var that = this;

		var blockQueryState = popManager.getBlockQueryState(pageSection, block);

		// Update the Year / Month Params (this is needed, so that the params are already set if filtering)
		blockQueryState[M.DATALOAD_VISIBLEPARAMS][M.URLPARAM_YEAR] = date.year();
		blockQueryState[M.DATALOAD_VISIBLEPARAMS][M.URLPARAM_MONTH] = date.month() + 1; // Month is 0-based, so gotta add 1
	},

	execute : function(pageSection, block, control, operation) {

		var that = this;

		// var calendar = block.find('.make-fullcalendar');
		var calendar = $(control.closest('.pop-calendar-controls').data('target'));

		// Load date / operation into the params
		var date = calendar.fullCalendar('getDate');

		// Extract year / month to add in query, depending on if it is 'next' or 'prev'
		if (operation == 'next') {
			date.add(1, 'months');
		}
		else {
			date = date.subtract(1, 'months');
		}

		that.setCalendarBlockParams(pageSection, block, date);
		
		// Delete previous "No Events found" messages
		popManager.closeMessageFeedback(block);
		
		// Fetch more events
		popFullCalendar.fetch(pageSection, block, calendar);
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popFullCalendar, ['initBlockRuntimeMemory', 'calendar']);
popJSLibraryManager.register(popFullCalendarControls, ['controlCalendarPrev', 'controlCalendarNext']);
