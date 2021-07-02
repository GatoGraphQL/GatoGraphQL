"use strict";
(function($){
window.pop.TypeaheadStorage = {
	
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
pop.JSLibraryManager.register(pop.TypeaheadStorage, ['removeLocalStorageItem']);