"use strict";
(function($){
window.pop.TypeaheadSuggestions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	renderDBObjectLayoutFromSuggestion : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, target);
		var moduleName = jsSettings['trigger-layout'];
		targets.click(function(e) {

			e.preventDefault();
			
			var link = $(this);
			var target = pop.TypeaheadSelectable.getTypeaheadTrigger($(link.data('target')));
			var value = link.data('objectid');
			var database_key = link.data('dbkey');
			var datum = pop.Manager.getDBObject(domain, database_key, value);

			// Trigger the select
			pop.DynamicRender.renderDBObjectLayoutFromDatum(domain, pageSection, block, target, datum, moduleName);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.TypeaheadSuggestions, ['renderDBObjectLayoutFromSuggestion']);
