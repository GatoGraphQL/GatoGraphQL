"use strict";
(function($){
window.pop.TargetFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	highlight : function(args) {

		var that = this;
		var targets = args.targets;
		
		targets.each(function() {
	
			// Must obtain the pageSection again and not use the one passed in the args, since the pageSection might be a different one
			// (eg: adding a comment from frame-addons and content added in main)
			var target = $(this);
			var pageSection = pop.Manager.getPageSection(target);
			pop.Manager.scrollToElem(pageSection, target, true);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.TargetFunctions, ['copyHeader', 'highlight']);
