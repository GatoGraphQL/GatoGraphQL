"use strict";
(function($){
window.pop.DateRange = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	dateRange : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		var format = pop.c.DATERANGE_FORMAT || 'DD/MM/YYYY';
		var settings = {
		
			// Comment Leo:
			// There's a bug: if these are set, then first time we click on "Today" it doesn't work
			// startDate: moment(),
			// endDate: moment(),
			autoUpdateInput: false,
			autoApply: true,
			linkedCalendars: true,
			// showDropdowns: true,
			showWeekNumbers: false,
			timePicker: false,			
			// opens: 'left',
			buttonClasses: ['btn'],
			applyClass: 'btn-success',
			cancelClass: 'btn-warning',
			format: format,
			separator: pop.c.DATERANGE_SEPARATOR,
			locale: {
				applyLabel: pop.c.DATERANGE_APPLY,
				cancelLabel: pop.c.DATERANGE_CANCEL,
				fromLabel: pop.c.DATERANGE_FROM,
				toLabel: pop.c.DATERANGE_TO,
				customRangeLabel: pop.c.DATERANGE_CUSTOMRANGE,
				daysOfWeek: pop.c.DATERANGE_DAYSOFWEEK,
				monthNames: pop.c.DATERANGE_MONTHNAMES,
				firstDay: 1
			}
			// Comment Leo 12/11/2014: since adding MESYM v4 with fixed panels, we must attach the opening window to the pageSection
			// parentEl : '#'+pageSection.attr('id')
		};

		var daterange_past = { 
			maxDate: moment().add(1, 'days')
		};
		var daterange_future = { 
			minDate: moment().subtract(1, 'days')
		};	
		var opens_right = {opens: 'right'};
		var opens_left = {opens: 'left'};

		// elem.find('.make-daterangepicker').each(function() {
		targets.each(function() {

			var daterange = $(this);
			
			// Comment Leo 12/11/2014: there's a bug, if opening to right it shows first the "to" side and then the "from" side on small screens (eg: mobile), so then open always to the left
			var settings_opens = daterange.hasClass('opens-left') ? opens_left : opens_right;
			// var settings_opens = opens_left;
			
			// Comment Leo: the container must be pop.c.DOMCONTAINER_ID so that the daterange can step out the boundaries of the pageSection, or in the addons pageSection it looks ugly
			var container_settings = {
				parentEl : '#'+pop.Manager.getDOMContainer(pageSection, daterange).attr('id')
			};
			var daterange_settings = $.extend({}, settings, settings_opens, container_settings);

			// Scope
			if (daterange.hasClass('daterange-future') && !daterange.hasClass('daterange-past')) {
				$.extend(daterange_settings, daterange_future);
			}
			else if (daterange.hasClass('daterange-past') && !daterange.hasClass('daterange-future')) {
				$.extend(daterange_settings, daterange_past);
			}

			// Use Time?
			if (daterange.hasClass('timepicker')) {
				var timeformat = pop.c.DATERANGE_TIMEFORMAT || 'h:mm A'; // 12hs format
				daterange_settings.locale.format = format+' '+timeformat;
				var timePicker = {
					autoApply: false,
					timePicker: true,
					timePicker24Hour: (timeformat != 'h:mm A'), // If we change the time locale, then it will go towards the H:mm format, then use 24hs
					timePickerIncrement: 15
					// locale : {
					// 	format: pop.c.DATERANGE_FORMAT_TIMEPICKER || 'DD/MM/YYYY h:mm A',
					// }
				};
				$.extend(daterange_settings, timePicker);
			}

			// Update the value in the input when clicking "Apply". It works together with autoUpdateInput=false, because
			// otherwise it also updates the value when clicking on Cancel
			daterange.on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
			});
			daterange.daterangepicker(
				daterange_settings,
				that.callback
			);

			// Comment Leo 05/12/2016: commented since adding autoUpdateInput=false
			// // Since daterangepicker v2.1.11, it also sets the initial value on the input, but without actually calling 'apply', so the values on the hidden inputs are not set
			// // To fix it, just remove the initial value, which is wrong anyway
			// if (!daterange.siblings('.from').val()) {
			// 	daterange.val('');
			// }
		});
	},

	//-------------------------------------------------
	// PROTECTED functions
	//-------------------------------------------------

	callback : function(start, end) {

		var that = this;
		var dateinput = that.element;

		dateinput.siblings('.from').val(start.format('YYYY-MM-DD'));
		dateinput.siblings('.to').val(end.format('YYYY-MM-DD'));

		// timePicker
		if (dateinput.siblings('.fromtime').length) {
			dateinput.siblings('.fromtime').val(start.format('HH:mm'));
			dateinput.siblings('.totime').val(end.format('HH:mm'));
		}
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.DateRange, ['dateRange']);