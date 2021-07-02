"use strict";
(function($){
window.pop.Notifications = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	timeFromNow : function(args) {

		var that = this;
		var targets = args.targets;

		targets.each(function() {

			var target = $(this);
			that.execTimeFromNow(target);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' functions
	//-------------------------------------------------
	execTimeFromNow : function(target) {

		var that = this;

		// Check if the target still exists
		if (($('#'+target.attr('id'))).length) {

			// Requires moment.js
			var time = target.data('time');
			var format = target.data('format');
			target.html(moment(time, format).fromNow());

			// Keep the time updated
			setTimeout(function () {
				
				that.execTimeFromNow(target);
			}, 60000); // Once every 60 seconds
		}
	}	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Notifications, ['timeFromNow']);
