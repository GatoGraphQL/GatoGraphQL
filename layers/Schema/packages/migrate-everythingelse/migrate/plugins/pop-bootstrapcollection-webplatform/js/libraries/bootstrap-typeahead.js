"use strict";
(function($){
window.pop.BootstrapTypeahead = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	selectableTypeaheadTrigger : function(args) {

		var that = this;
			
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Trigger close event
		targets.on('close.bs.alert', function() {
			
			var alert = $(this);
			var typeahead = alert.closest('.pop-typeahead');

			// Re-enable the Typeahead (if disabled because of max-selected limit reached)
			var disable = pop.TypeaheadValidate.getElementsToDisable(typeahead);
			disable.attr('disabled', false);
			disable.removeClass('disabled');

			// Trigger the value for other components (eg: close the map marker in typeahead-map.js)
			var val = alert.children('input[type="hidden"]').val();
			typeahead.triggerHandler('close', [alert, val]);
		});
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.BootstrapTypeahead, ['selectableTypeaheadTrigger']);
