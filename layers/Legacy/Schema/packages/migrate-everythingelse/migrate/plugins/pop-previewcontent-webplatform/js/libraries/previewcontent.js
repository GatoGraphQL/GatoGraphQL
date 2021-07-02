"use strict";
(function($){
window.pop.PreviewContent = {

	//-------------------------------------------------
	// PUBLIC functions
	//-------------------------------------------------

	loadResourcesDB : function(args) {

		var that = this;
		var url = args.url, path = args.path, config = args.config;

		// If originally it is the home URL
		if (!path) {

			// If it is the preview, then it has parameter 'preview' = 1
			var preview = getParam(pop.c.PARAMS_PREVIEW, url);
			if (preview == '1') {

				// We know what type of post if it, because we are adding the path to the edit URL
				var postPath = getParam(pop.c.PARAMS_PATH, url);
				if (postPath) {

					// Change the config from home to single, under the corresponding path
					args.resourcesDB = config.resources.single[postPath];
				}
			}
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
pop.JSLibraryManager.register(pop.PreviewContent, ['loadResourcesDB']);
