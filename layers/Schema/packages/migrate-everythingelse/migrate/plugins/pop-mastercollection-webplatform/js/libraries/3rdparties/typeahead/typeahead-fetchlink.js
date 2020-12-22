"use strict";
(function($){
window.pop.TypeaheadFetchLink = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	fetchlinkTypeahead : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
		
		targets.each(function() {

			var typeahead = $(this);	

			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, typeahead);		
			var components = jsSettings['dataset-components'];
			pop.Typeahead.typeahead(domain, pageSection, block, typeahead, components);
			
			// Process the "selected" trigger
			typeahead.bind('typeahead:selected', function(obj, datum, name) {	  
				
				pop.Manager.click(datum.url);
			});
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.TypeaheadFetchLink, ['fetchlinkTypeahead']);
