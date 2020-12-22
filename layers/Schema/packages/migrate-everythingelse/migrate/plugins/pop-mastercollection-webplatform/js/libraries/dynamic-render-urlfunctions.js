"use strict";
(function($){
window.pop.DynamicRenderURLFunctions = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	renderDBObjectLayoutFromURLParam : function(args) {

		var that = this;
		var intercepted = args.intercepted;

		// Only execute if the trigger is an intercepted URL. If not, this functionality is already implemented on the server-side
		if (intercepted) {
			
			var domain = args.domain, pageSection = args.pageSection, block = args.block, targets = args.targets;
			targets.each(function() {

				var target = $(this);

				var urlparam = target.data('urlparam');
				if (urlparam) {

					var url = pop.Manager.getBlockTopLevelURL(domain, block);
					var values = getParam(urlparam, url);
					if (values) {

						// values may either be a single value or an object. Cater for both cases.
						if ($.type(values) !== "object") {

							values = {1: values};
						}

						var jsSettings = pop.Manager.getJsSettings(domain, pageSection, block, target);
						var moduleName = jsSettings['trigger-layout'];
						var database_key = target.data('database-key');
						$.each(values, function(key, value) {

							var datum = pop.Manager.getDBObject(domain, database_key, value);

							// Trigger the select
							pop.DynamicRender.renderDBObjectLayoutFromDatum(domain, pageSection, block, target, datum, moduleName);
						});
					}
				}
			});
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.DynamicRenderURLFunctions, ['renderDBObjectLayoutFromURLParam']);
