"use strict";
(function($){
window.pop.BootstrapHooks = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	collapseCookie : function(args) {

		var that = this;
		var target = args.target, collapse = args.collapse;
		
		// Support for Bootstrap Collapse: execute JS instead of just adding the class, or it doesn't work
		$(document).ready(function($) {
			
			target.collapse(collapse);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapHooks, ['collapseCookie']);
