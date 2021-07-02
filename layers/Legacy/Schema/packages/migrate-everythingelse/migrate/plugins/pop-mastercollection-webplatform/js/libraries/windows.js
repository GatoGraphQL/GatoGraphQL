"use strict";
(function($){
window.pop.Window = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	windowSize : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			var control = $(this);
			var target = $(control.data('target'));

			if (target.length) {

				var action = control.data('toggle');
				if (action == 'window-fullsize') {
					that.fullsize(target);
				}
				else if (action == 'window-maximize') {
					that.maximize(target);
				}
				else if (action == 'window-minimize') {
					that.minimize(target);
				}
			}
		});
	},

	//-------------------------------------------------
	// 'PRIVATE' FUNCTIONS
	//-------------------------------------------------

	getSize : function(target) {
	
		var that = this;
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
	
		var that = this;
		target
			.removeClass('maximized minimized')
			.addClass('fullsize');
		target.triggerHandler('fullsize.bs.window');
	},
	maximize : function(target) {
	
		var that = this;
		target
			.removeClass('fullsize minimized')
			.addClass('maximized');
		target.triggerHandler('maximized.bs.window');
	},
	minimize : function(target) {
	
		var that = this;
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
pop.JSLibraryManager.register(pop.Window, ['windowSize']);