"use strict";
(function($){
window.pop.TypeaheadValidate = {
	
	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	validateMaxSelected : function(pageSection, block, typeahead) {
	
		var that = this;

		// Count how many items, and if we reach the max, disable the typeahead
		if (typeahead.data('max-selected')) {

			var numSelected = typeahead.find('input[type="hidden"]').length;
			if (numSelected == typeahead.data('max-selected')) {

				// Disable the typeahead
				var disable = that.getElementsToDisable(typeahead);
				disable.attr('disabled', 'disabled');
				disable.addClass('disabled');
			}	
		}
	},

	getElementsToDisable : function(typeahead) {

		var that = this;
		return typeahead.children('div.pop-operation').find('a, button, input');
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.TypeaheadValidate, []);