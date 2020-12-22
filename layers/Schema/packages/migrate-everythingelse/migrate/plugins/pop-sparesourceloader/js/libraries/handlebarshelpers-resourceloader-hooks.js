"use strict";

(function($){
window.pop.SPAResourceLoaderHandlebarsHelperHooks = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------
	handlebarsHelperEnterModuleResponse : function(args) {
	
		var that = this;
		var context = args.context, domain = args.domain, bId = args.bId, response = args.response;

		// Comment Leo 23/11/2017: actually we do need it! For lazy-loading content
		// Eg: notification-layout, inside the pagesection-top notifications, when initializing the lazy-load must print the link
		// // Comment Leo 22/11/2017: it makes no sense to print the styles in the body in the client, since loading
		// // the css files through the resourceLoader: it starts fetch after the user clicks on the link, 
		// // which is far more efficient than waiting until adding the HTML code to the DOM
		// Add the CSS style links in the body?
		if (pop.c.PRINTTAGSINBODY) {

			var resources = context[pop.c.JS_RESOURCES];
			if (resources) {

				var resourceTags = pop.SPAResourceLoader.includeResources(domain, bId, resources, true);
				args.response = resourceTags + response;
			}
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.SPAResourceLoaderHandlebarsHelperHooks, ['handlebarsHelperEnterModuleResponse']);
