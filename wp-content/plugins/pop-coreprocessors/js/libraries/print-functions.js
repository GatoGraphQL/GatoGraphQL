(function($){
window.popPrintFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	printWindow : function(args) {

		var that = this;

		// Print only if parameter "action=print" is in the URL
		// This way, we can control when to print automatically and when not (eg: just to show the Print themeMode in GetPoP website)
		var action = getParam(M.URLPARAM_ACTION, window.location.href);
		if (action == M.URLPARAM_ACTION_PRINT) {
			
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
popJSLibraryManager.register(popPrintFunctions, ['printWindow']);
