"use strict";

(function($){
window.pop.ResourceLoaderHandlebarsHelperHooks = {

	config: {},

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

				var resourceTags = that.includeResources(domain, bId, resources);
				args.response = resourceTags + response;
			}
		}
	},

	includeResources : function(domain, blockId, resources) {

		var that = this;
		// Check we have a config for this domain
		var config = that.config[domain];
		
		// Map the resources to their tags
		var tags = resources.map(function(resource) {

			// If destroying the pageSectionPage, the corresponding 'in-body' styles will also be deleted, and other pages using those styles will be affected.
			// Then, simply load again those removed resources (scripts and styles)
			var source = config.sources[resource];
			if (config.types[pop.c.RESOURCELOADER.TYPES.CSS].indexOf(resource) >= 0) {

				var tag = '<link rel="stylesheet" href="{0}">'.format(source);
				return tag;
			}
			else if (config.types[pop.c.RESOURCELOADER.TYPES.JS].indexOf(resource) >= 0) {
				
				var tag = '<script type="text/javascript" src="{0}"></script>'.format(source);
				return tag;
			}
			return '';
		});

		return tags.join('');
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.ResourceLoaderHandlebarsHelperHooks, ['handlebarsHelperEnterModuleResponse']);
