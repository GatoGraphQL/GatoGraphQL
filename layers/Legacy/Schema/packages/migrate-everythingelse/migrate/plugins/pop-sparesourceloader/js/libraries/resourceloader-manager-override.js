"use strict";
(function($){
window.pop.ManagerSPAResourceLoaderOverride = {

	init : function() {

		var that = this;
		
		// Override pop.Manager's function with the one from this object
		pop.Manager.checkExecuteProcessPageSectionResponse = that.checkExecuteProcessPageSectionResponse;
	},

	checkExecuteProcessPageSectionResponse : function(domain, pageSection, url, fetchUrl, response, options) {

		var that = this;

		// // Check if the resources are loaded
		// // Allow pop.SPAResourceLoader to hook in
		// // The end result will be as if doing: var resourcesLoaded = !pop.c.USECODESPLITTING || pop.SPAResourceLoader.areResourcesLoadedForURL(fetchUrl);
		// var args = {
		// 	loaded: !pop.c.USECODESPLITTING,
		// 	fetchUrl: fetchUrl, 
		// };
		// pop.JSLibraryManager.execute('areResourcesLoaded', args);
		// if (args.loaded) {
		if (pop.SPAResourceLoader.areResourcesLoadedForURL(fetchUrl)) {
			
			// Resources loaded => Process
			pop.Manager.executeProcessPageSectionResponse(domain, pageSection, url, response, options);
		}
		else {
		
			// Not loaded => check again in 100ms
			setTimeout(function () {
				
				that.checkExecuteProcessPageSectionResponse(domain, pageSection, url, fetchUrl, response, options)
			}, 100);
		}
	},
};
})(jQuery);

//-------------------------------------------------
// Initialize
//-------------------------------------------------
if (pop.c.USECODESPLITTING) {

	// pop.ManagerSPAResourceLoaderOverride.init();
	pop.JSLibraryManager.register(pop.ManagerSPAResourceLoaderOverride, ['init']);
}
