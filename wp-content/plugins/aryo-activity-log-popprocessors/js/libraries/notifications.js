(function($){
popNotifications = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	timeFromNow : function(args) {

		var t = this;
		var targets = args.targets;

		targets.each(function() {

			var target = $(this);
			t.execTimeFromNow(target);
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' functions
	//-------------------------------------------------
	execTimeFromNow : function(target) {

		var t = this;

		// Check if the target still exists
		if (($('#'+target.attr('id'))).length) {

			// Requires moment.js
			var time = target.data('time');
			var format = target.data('format');
			target.html(moment(time, format).fromNow());

			// Keep the time updated
			setTimeout(function () {
				
				t.execTimeFromNow(target);
			}, 60000); // Once every 60 seconds
		}
	},
	
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popNotifications, ['timeFromNow']);