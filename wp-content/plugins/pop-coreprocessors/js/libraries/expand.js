"use strict";
(function($){
window.popExpand = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	expandJSKeys : function(args) {
	
		var that = this;
		var context = args.context;
		
		if (context[M.JS_FONTAWESOME]) {
			context.fontawesome = context[M.JS_FONTAWESOME];
		}
		if (context[M.JS_DESCRIPTION]) {
			context.description = context[M.JS_DESCRIPTION];
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popExpand, ['expandJSKeys']);
