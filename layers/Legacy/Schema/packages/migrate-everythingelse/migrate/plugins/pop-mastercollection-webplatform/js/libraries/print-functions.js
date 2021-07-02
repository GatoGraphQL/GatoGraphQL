"use strict";
(function($){
window.pop.PrintFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	printWindow : function(args) {

		var that = this;

		// Print only if parameter "action=print" is in the URL
		// This way, we can control when to print automatically and when not (eg: just to show the Print themeMode in GetPoP website)
		var actions = getParam(pop.c.URLPARAM_ACTIONS+'[]', window.location.href);
		if (actions == pop.c.URLPARAM_ACTION_PRINT) {
			
			$(document).ready(function($) {
				window.print();
			});
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.PrintFunctions, ['printWindow']);
