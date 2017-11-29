"use strict";
(function($){
window.popTypeaheadFetchLink = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	fetchlinkTypeahead : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, /*pageSectionPage = args.pageSectionPage, */block = args.block, targets = args.targets;
		
		popTypeahead.typeahead(domain, pageSection, /*pageSectionPage, */block, targets);
		
		targets.each(function() {

			var typeahead = $(this);	

			// Process the "selected" trigger
			typeahead.bind('typeahead:selected', function(obj, datum, name) {	  
				
				var selected = $(this);
				var typeahead = selected.closest('.pop-typeahead');
				popManager.click(datum.url, '', typeahead);
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
popJSLibraryManager.register(popTypeaheadFetchLink, ['fetchlinkTypeahead']);
