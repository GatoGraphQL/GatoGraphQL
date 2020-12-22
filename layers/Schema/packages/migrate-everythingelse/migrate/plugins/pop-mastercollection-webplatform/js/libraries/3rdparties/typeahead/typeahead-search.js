"use strict";
(function($){
window.pop.TypeaheadSearch = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	typeaheadSearchBtn : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function() {

			var control = $(this);
			var typeahead = control.closest('.pop-typeahead');
			that.executeTypeaheadSearch(pageSection, block, typeahead);
		});
	},
	typeaheadSearchInput : function(args) {
	
		var that = this;
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.keypress(function(e) {

			// Pressed Enter key
			if (event.which == 13) {

				event.preventDefault();
				var searchbox = $(this);

				var typeahead = searchbox.closest('.pop-typeahead');
				
				// Close the typeahead, not needed anymore
				var input = typeahead.find('input[type="text"].tt-input');
				input.typeahead('close');

				that.executeTypeaheadSearch(pageSection, block, typeahead);
			}
		});
	},

	//-------------------------------------------------
	// PUBLIC but not EXPOSED functions
	//-------------------------------------------------

	executeTypeaheadSearch : function(pageSection, block, typeahead) {
	
		var that = this;
		var input = typeahead.find('input[type="text"].tt-input');

		// Trigger the search: get the url from the block dataload-source, and replace %QUERY with the value of the typeahead
		var url = pop.Manager.getQueryUrl(pageSection, block);
		var val = input.typeahead('val');
		if (val) {
			url = url.replace(pop.c.JSPLACEHOLDER_QUERY/*'%QUERY'*/, input.typeahead('val'));
			pop.Manager.click(url, '', typeahead);
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.TypeaheadSearch, ['typeaheadSearchBtn', 'typeaheadSearchInput']);
