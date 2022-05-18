"use strict";
(function($){
window.pop.FullCalendar = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	calendar : function(args) {

		var that = this;

		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		var pageSectionPage = pop.Manager.getPageSectionPage(block);
		pageSectionPage.one('destroy', function() {

			targets.fullCalendar('destroy');
		});
		
		// If when opening the page, the pageSectionPage is not active (eg: alt+click on the link), then the calendar will not show up
		// So gotta call method 'render' whenever that page is first opened
		if (pageSectionPage.hasClass('tab-pane') && !pop.Manager.isActive(pageSectionPage)) {

			// if (pageSectionPage.hasClass('tab-pane')) {

			pageSectionPage.one('shown.bs.tabpane', function() {
				
				targets.fullCalendar('render');
			});
			// }
		}

		// When re-rendering a block, check if the operation is REPLACE (= filtering), then delete all the current events
		block.on('beforeRender', function(e, options) {

			var block = $(this);
			
			if (options.operation == pop.c.URLPARAM_OPERATION_REPLACE) {
	
				pop.FullCalendarMemory.resetBlockRuntimeMemory(pageSection, block);
			}
		});

		// When new events are added, re-draw the calendar
		block.on('addedEvents', function() {
			
			that.execCalendar(domain, pageSection, block, targets, 'update');

			// Dispatch a window resize so that the Calendar / Google map gets updated
			windowResize();
		});

		that.execCalendar(domain, pageSection, block, targets, 'new');
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	execCalendar : function(domain, pageSection, block, targets, state) {

		var that = this;

		var mempage = pop.FullCalendarMemory.getRuntimeMemoryPage(pageSection, block);
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

					var events;
					events = mempage.events;
					callback(events);
				},
				defaultDate: date,
				fixedWeekCount: false,
				height: 'auto',
				contentHeight: 'auto',
				dayNamesShort: pop.c.DATERANGE_DAYSOFWEEK // Change days of week from 'Mon' to 'Mo' so that it fits fine on small screens
			};
		}

		targets.each(function() {

			var calendar = $(this);

			// Create fullCalendar elements
			that.execFullCalendar(domain, pageSection, block, calendar, state, options);
		});

		// Set this year/month as loaded
		that.setDateLoaded(pageSection, block, date);
	},

	execFullCalendar : function(domain, pageSection, block, calendar, state, options) {

		var that = this;

		// Create fullCalendar elements
		if (state == 'new') {
			
			// Integrate the custom options for the Calendar
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, calendar);
			var customOptions = jsSettings.options || {};
			var calendarOptions = $.extend({}, options, customOptions);
			var layouts = jsSettings.layouts;

			calendarOptions.eventRender = function(event, element) {
				
				that.renderEvent(pageSection, block, layouts, event, element);
			};
			calendarOptions.eventAfterAllRender = function(view) {

				that.renderEventAfterAll(pageSection, block, layouts);
			};

			calendar.fullCalendar(calendarOptions);
		}
		else if (state == 'update') {

			calendar.fullCalendar('refetchEvents');
		}
	},

	setDateLoaded : function(pageSection, block, date) {

		var that = this;
		var mempage = pop.FullCalendarMemory.getRuntimeMemoryPage(pageSection, block);

		mempage.loadedDates[date.year()+'-'+(date.month()+1)] = true;
	},

	isDateLoaded : function(pageSection, block, date) {

		var that = this;
		var mempage = pop.FullCalendarMemory.getRuntimeMemoryPage(pageSection, block);

		return mempage.loadedDates[date.year()+'-'+(date.month()+1)] === true;
	},

	getDate : function(pageSection, block) {

		var that = this;
		var blockQueryState = pop.Manager.getBlockQueryState(pageSection, block);

		// Make sure to not include the leading zeros
		var year = parseInt(blockQueryState[pop.c.DATALOAD_VISIBLEPARAMS][pop.c.URLPARAM_YEAR]);
		var month = parseInt(blockQueryState[pop.c.DATALOAD_VISIBLEPARAMS][pop.c.URLPARAM_MONTH]);

		// Migration to FullCalendar 2.0: use Moment to generate the date
		// http://arshaw.com/fullcalendar/docs/current_date/defaultDate/
		return moment(year + '-' + month + '-01', 'YYYY-M-DD');
	},

	renderEvent : function(pageSection, block, layouts, event, element) {

		var that = this;
		var mempage = pop.FullCalendarMemory.getRuntimeMemoryPage(pageSection, block);

		// If the html has already been generated, then use it (eg: on popstate)
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
			
			var dbKeys = pop.Manager.getDatabaseKeys(domain, pageSection, block);
			var dbKey = dbKeys.id;
			html = '';
			pop.JSRuntimeManager.setBlockURL(domain, block);
			$.each(layouts, function(index, layout) {
				html += pop.Manager.getModuleHtml(domain, pageSection, block, layout, options, dbKey, eventId);
			});

			// Save the html for next time
			mempage.eventsHtml[domain][eventId] = html;
		}

		element.html(html);
	},

	renderEventAfterAll : function(pageSection, block, layouts) {

		var that = this;
		var mempage = pop.FullCalendarMemory.getRuntimeMemoryPage(pageSection, block);

		// Upon triggering merged, the popover scripts with code $(document).one('component:merged', ... will get executed
		// run this before executing the JS
		pop.Manager.triggerHTMLMerged();

		// If the date is already loaded, then the corresponding html is also already generated, then
		// we need to run the javascript once again starting on the module
		if (mempage.runJS) {

			// Clear flag
			mempage.runJS = false;
			
			// Use 'full' because just the 'last' session ids is not good enough: these will only hold the ids
			// for the last retrieved month. Eg: we're in july and the 'last' ids are for july, then we move to august,
			// 'last' ids will be august, clicking prev back to July we don't have the july ids anymore on 'last'
			// By executing on 'full', we'll be executing over absolutely all generated ids, ie: both july and august. However,
			// because august events were taken out of the DOM, then it won't execute anything on them; august events 
			// will not exist on the calendar anymore by then
			var query_urls = pop.Manager.getQueryMultiDomainUrls(pageSection, block);
			$.each(query_urls, function(domain, query_url) {
				$.each(layouts, function(index, layout) {
					pop.Manager.runJSMethods(domain, pageSection, block, layout, 'full');
				});
			});
		}
	},

	fetch : function(pageSection, block, calendar) {

		var that = this;

		var date = that.getDate(pageSection, block);
		// Month is 0-based (http://arshaw.com/fullcalendar/docs/current_date/gotoDate/) so substract 1		
		calendar.fullCalendar( 'gotoDate', date);

		if (that.isDateLoaded(pageSection, block, date)) {

			return;
		}

		// Submit to fetch the Block data, when it comes back process it through a handler on the gdFullCalendar side
		pop.Manager.fetchBlock(pageSection, block, {operation: pop.c.URLPARAM_OPERATION_APPEND});
	}
};
})(jQuery);

(function($){
window.pop.FullCalendarControls = {
	
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

		var blockQueryState = pop.Manager.getBlockQueryState(pageSection, block);

		// Update the Year / Month Params (this is needed, so that the params are already set if filtering)
		blockQueryState[pop.c.DATALOAD_VISIBLEPARAMS][pop.c.URLPARAM_YEAR] = date.year();
		blockQueryState[pop.c.DATALOAD_VISIBLEPARAMS][pop.c.URLPARAM_MONTH] = date.month() + 1; // Month is 0-based, so gotta add 1
	},

	execute : function(pageSection, block, control, operation) {

		var that = this;

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
		pop.Manager.closeFeedbackMessage(block);
		
		// Fetch more events
		pop.FullCalendar.fetch(pageSection, block, calendar);
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.FullCalendar, ['calendar']);
pop.JSLibraryManager.register(pop.FullCalendarControls, ['controlCalendarPrev', 'controlCalendarNext']);
