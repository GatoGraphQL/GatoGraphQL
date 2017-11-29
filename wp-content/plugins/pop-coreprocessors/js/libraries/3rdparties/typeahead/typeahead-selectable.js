"use strict";
(function($){
window.popTypeaheadSelectable = {
	
	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	fillTypeahead : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		// var domain = getDomain(block.data('toplevel-url'));
		targets.each(function() {

			var typeahead = $(this);

			var urlparam = typeahead.data('urlparam');
			if (urlparam) {

				var url = popManager.getBlockTopLevelURL(block);//popManager.getTargetParamsScopeURL(block)/*block.data('paramsscope-url')*/;
				var values = getParam(urlparam, url);
				if (values) {

					var database_key = typeahead.data('database-key');
					$.each(values, function(key, value) {

						var datum = popManager.getItemObject(domain, database_key, value);

						// Trigger the select
						popTypeahead.selectTypeahead(domain, pageSection, block, typeahead, datum);
					});

					// If we are inside a collapsed widget (eg: in the Add Post addons pageSection), then open the collapse
					popBootstrap.execOpenParentCollapse(typeahead);
				}
			}
		});
	},

	selectDatum : function(args) {

		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		targets.click(function(e) {

			e.preventDefault();
			
			var link = $(this);
			var typeahead = $(link.data('target'));
			var value = link.data('objectid');
			var database_key = link.data('db-key');
			// var domain = getDomain(block.data('toplevel-url'));
			var datum = popManager.getItemObject(domain, database_key, value);

			// Trigger the select
			popTypeahead.selectTypeahead(domain, pageSection, block, typeahead, datum);
		});
	},

	selectableTypeahead : function(args) {
	
		var that = this;
		var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;

		popTypeahead.typeahead(domain, pageSection, block, targets);

		targets.each(function() {

			var typeahead = $(this);

			popTypeaheadValidate.validateMaxSelected(pageSection, block, typeahead);
			
			// Process the "selected" trigger
			typeahead.bind('typeahead:selected', function(obj, datum, name) {	  

				var typeahead = $(this);
				popTypeahead.selectTypeahead(domain, pageSection, block, typeahead, datum);
			});
		});
	},

	selectableTypeaheadTrigger : function(args) {

		var that = this;
			
		var pageSection = args.pageSection, block = args.block, targets = args.targets;

		// Trigger close event
		targets.on('close.bs.alert', function() {
			
			var alert = $(this);
			var typeahead = alert.closest('.pop-typeahead');

			// Re-enable the Typeahead (if disabled because of max-selected limit reached)
			var disable = popTypeaheadValidate.getElementsToDisable(typeahead);
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
popJSLibraryManager.register(popTypeaheadSelectable, ['fillTypeahead', 'selectableTypeahead', 'selectDatum', 'selectableTypeaheadTrigger']);
