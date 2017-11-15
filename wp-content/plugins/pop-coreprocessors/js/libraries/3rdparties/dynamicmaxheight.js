"use strict";
(function($){
window.popDynamicMaxHeight = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	dynamicMaxHeight : function(args) {
	
		var that = this;
		var targets = args.targets;

		targets.dynamicMaxHeight();
	}
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popDynamicMaxHeight, ['dynamicMaxHeight']);