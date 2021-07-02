"use strict";
(function($){
window.pop.PrettyPrint = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	prettyPrint : function(args) {

		var that = this;
		var targets = args.targets;

		targets.find('.prettyprint').addBack('.prettyprint').each(function(e) {

			// Logic taken from https://stackoverflow.com/questions/1270221/how-to-format-code-in-html-css-js-php
			var elem = $(this);
			elem.html(prettyPrintOne(elem.html()));
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.PrettyPrint, ['prettyPrint']);