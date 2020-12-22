"use strict";
(function($){
window.pop.DynamicRender = {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	render : function(domain, pageSection, block, moduleName, targetContainer, extendContext, options) {
	
		var that = this;

		options = options || {};
		options = $.extend(options, {extendContext: extendContext});
		
		pop.JSRuntimeManager.setBlockURL(domain, block);
		var html = pop.Manager.getModuleHtml(domain, pageSection, block, moduleName, options);
		var merged = pop.Manager.mergeHtml(html, targetContainer, options['operation']);
		pop.Manager.runJSMethods(domain, pageSection, block, moduleName, 'last');

		// Delete the session ids before starting a new session
		pop.JSRuntimeManager.deleteBlockLastSessionIds(domain, pageSection, block, moduleName);

		return merged;
	},

	renderDBObjectLayoutFromDatum : function(domain, pageSection, block, target, datum, moduleName) {
	
		var that = this;

		// Allow the typeahead to not print the layout if it is a repeated input
		var args = {
			render: true,
			domain: domain, 
			pageSection: pageSection, 
			block: block, 
			target: target, 
			datum: datum,
			moduleName: moduleName,
		};
		pop.JSLibraryManager.execute('executeDBObjectLayoutRender', args);
		if (args.render) {
		
			var pssId = pop.Manager.getSettingsId(pageSection);
			var bsId = pop.Manager.getSettingsId(block);
			var context = {dbObject: datum};

			// Generate the code and append
			var targetContainer = pop.Manager.getMergeTarget(target, moduleName);
			that.render(domain, pageSection, block, moduleName, targetContainer, context);
			
			target.triggerHandler('dbObjectLayoutRendered', [domain, pageSection, block, target, datum]);
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
// pop.JSLibraryManager.register(pop.DynamicRender, []);
