"use strict";
(function($){
window.popTypeaheadStorage = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	removeLocalStorageItem : function(args) {

		var that = this;
		var path = args.path, key = args.key;

		// Delete the localStorage entries for the typeahead
		args.result = args.result || key.startsWith('__'+path);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popTypeaheadStorage, ['removeLocalStorageItem']);