(function($){
popWindow = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	windowSize : function(args) {
	
		var t = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);
			var target = $(control.data('target'));

			if (target.length) {

				var action = control.data('toggle');
				if (action == 'window-fullsize') {
					t.fullsize(target);
				}
				else if (action == 'window-maximize') {
					t.maximize(target);
				}
				else if (action == 'window-minimize') {
					t.minimize(target);
				}
			}
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getSize : function(target) {
	
		var t = this;
		if (target.hasClass('fullsize')) {
			return 'fullsize';
		}
		else if (target.hasClass('maximized')) {
			return 'maximized';
		}
		else if (target.hasClass('minimized')) {
			return 'minimized';
		}
		
		return null;
	},
	fullsize : function(target) {
	
		var t = this;
		target
			.removeClass('maximized minimized')
			.addClass('fullsize');
		target.triggerHandler('fullsize.bs.window');
	},
	maximize : function(target) {
	
		var t = this;
		target
			.removeClass('fullsize minimized')
			.addClass('maximized');
		target.triggerHandler('maximized.bs.window');
	},
	minimize : function(target) {
	
		var t = this;
		target
			.removeClass('fullsize maximized')
			.addClass('minimized');
		target.triggerHandler('minimized.bs.window');
	},

};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popWindow, ['windowSize']);