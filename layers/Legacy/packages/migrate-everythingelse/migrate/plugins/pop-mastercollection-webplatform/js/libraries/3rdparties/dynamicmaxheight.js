"use strict";
(function($){
window.pop.DynamicMaxHeight = {

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
pop.JSLibraryManager.register(pop.DynamicMaxHeight, ['dynamicMaxHeight']);