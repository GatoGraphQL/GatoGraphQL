"use strict";
(function($){
window.pop.Expand = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	expandJSKeys : function(args) {
	
		var that = this;
		var context = args.context;
		
		if (context[pop.c.JS_FONTAWESOME]) {
			context.fontawesome = context[pop.c.JS_FONTAWESOME];
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.Expand, ['expandJSKeys']);
