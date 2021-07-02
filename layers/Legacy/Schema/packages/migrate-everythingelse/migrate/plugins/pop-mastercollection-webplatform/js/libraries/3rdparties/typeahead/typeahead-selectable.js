"use strict";
(function($){
window.pop.TypeaheadSelectable = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	selectableTypeahead : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.each(function() {

			var typeahead = $(this);
			var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, typeahead);		
			var components = jsSettings['dataset-components'];
			var moduleName = jsSettings['trigger-layout'];
			var trigger = that.getTypeaheadTrigger(typeahead);

			pop.Typeahead.typeahead(domain, pageSection, block, typeahead, components);
			pop.TypeaheadValidate.validateMaxSelected(pageSection, block, typeahead);

			// Whenever a new item is rendered in the typeahead, validate its max-selected
			trigger.on('dbObjectLayoutRendered', function() {

				// Count how many items, and if we reach the max, disable the typeahead
				pop.TypeaheadValidate.validateMaxSelected(pageSection, block, typeahead);
			});
			
			// Process the "selected" trigger
			typeahead.bind('typeahead:selected', function(obj, datum, name) {	  

				var typeahead = $(this);
				var trigger = that.getTypeaheadTrigger(typeahead);

				// Render the item
				pop.DynamicRender.renderDBObjectLayoutFromDatum(domain, pageSection, block, trigger, datum, moduleName);

				// If the item was generated from a typeahead, tehn delete the typeahead string
				typeahead.find('input[type="text"].tt-input').typeahead('val', '');
			});
		});
	},

	executeDBObjectLayoutRender : function(args) {
	
		var that = this;
		var target = args.target, datum = args.datum;

		// Comment Leo 30/10/2017: when doing serverside-rendering, and first loading the website (eg: https://www.mesym.com/en/add-post/?related[0]=22199),
		// we will already have the item rendered. And since the database is not sent anymore, datum.id will be empty
		// so we must check for this condition too.

		// Check this value has not been added before
		if (!datum || !datum.id || target.filter('.'+pop.c.CLASS_TRIGGERLAYOUT).find('input[type="hidden"][value="'+datum.id+'"]').length > 0) {

			args.render = false;
		}
	},

	getTypeaheadTrigger : function(typeahead) {
	
		var that = this;
		return typeahead.children('.pop-trigger').find('.'+pop.c.CLASS_TRIGGERLAYOUT);
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.TypeaheadSelectable, ['selectableTypeahead', 'executeDBObjectLayoutRender']);
